<?php

namespace App\Http\Controllers;

use App\Exports\OrderExport;
use App\Http\Enums\OrderStatusEnum;
use App\Http\Enums\UserRoleEnum;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Category;
use App\Models\Company;
use App\Models\Order;
use App\Models\Pack;
use App\Models\Seller;
use App\Models\User;
use Buglinjo\LaravelWebp\Facades\Webp;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orders = Order::when($request->search, function ($query) use ($request) {
                $query->orWhereHas('user', function ($query) use ($request) {
                    $query->where('name', 'like', "%{$request->search}%");
                    $query->orWhere('email', 'like', "%{$request->search}%");
                });
                $query->orWhereHas('company', function ($query) use ($request) {
                    $query->where('name', 'like', "%{$request->search}%");
                });
                $query->orWhereHas('pack', function ($query) use ($request) {
                    $query->where('title', 'like', "%{$request->search}%");
                });
                $query->orWhere('id', $request->search);
            })
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->seller, function ($query) use ($request) {
                $query->where('user_id', $request->seller);
            })
            ->when($request->payment_method, function ($query) use ($request) {
                $query->where('payment_method', $request->payment_method);
            })
            ->when($request->category, function ($query) use ($request) {
                $query->whereHas('company', function ($query) use ($request) {
                    $query->whereHas('categories', function ($query) use ($request) {
                        $query->where('category_id', $request->category);
                    });
                });
            })
            ->when($request->city, function ($query) use ($request) {
                $query->whereHas('company', function ($query) use ($request) {
                    $query->where('city', $request->city);
                });
            })
            ->when($request->initial_approved_at, function ($query) use ($request) {
                $query->whereDate('approved_at', '>=', $request->initial_approved_at);
            })
            ->when($request->final_approved_at, function ($query) use ($request) {
                $query->whereDate('approved_at', '<=', $request->final_approved_at);
            })
            ->when($request->initial_expire_at, function ($query) use ($request) {
                $query->whereDate('expire_at', '>=', $request->initial_expire_at);
            })
            ->when($request->final_expire_at, function ($query) use ($request) {
                $query->whereDate('expire_at', '<=', $request->final_expire_at);
            })
            ->where(function ($query) {
                if (Auth::user()->role !== UserRoleEnum::Admin->value) {
                    $query->where('user_id', Auth::id());
                }
            })
            ->latest()
            ->paginate(50);

        $sellers = Seller::all();
        $categories = Category::whereHas('companies')->where('status', true)->get();
        $cities = Company::distinct()->orderBy('city', 'asc')->pluck('city')->toArray();

        return view('orders.index', compact('orders', 'sellers', 'categories', 'cities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Company::where(function ($query) {
            if (Auth::user()->role === UserRoleEnum::Seller->value) {
                $query->where('user_id', Auth::id());
            }
        })->get();
        $packs = Pack::all();
        return view('orders.create', compact('companies', 'packs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();

            $company = Company::find($validated['company_id']);

            $validated['user_id'] = $company->user_id;
            $validated['uuid'] = Str::uuid();

            if (isset($validated['image'])) {
                if (!file_exists(public_path('storage/orders'))) {
                    mkdir(public_path('storage/orders'), 0777, true);
                }

                $fileName = 'orders/' . uniqid() . '.webp';

                if (in_array($validated['image']->getClientOriginalExtension(), ['jpg', 'jpeg', 'png'])) {
                    $file = Webp::make($validated['image']);

                    if ($file->save(public_path('storage/' . $fileName))) {
                        $validated['image'] = $fileName;
                    }
                } else {
                    $validated['image'] = $validated['image']->store('orders', 'public');
                }
            }

            Order::create($validated);

            DB::commit();

            Alert::toast('Pedido cadastrado com sucesso.', 'success');
            return Redirect::route('orders.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            Alert::toast('Falha ao cadastrar pedido.', 'error');
            return back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $companies = Company::all();
        $packs = Pack::all();
        return view('orders.edit', compact('order', 'companies', 'packs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();

            if ($order->status === OrderStatusEnum::Opened->value && $validated['status'] === OrderStatusEnum::Accomplished->value) {
                $validated['approved_at'] = now();
                $validated['expire_at'] = $order->getExpireAt();
            }

            if ($order->status === OrderStatusEnum::Opened->value && $order->status === OrderStatusEnum::Cancelled->value) {
                $validated['canceled_at'] = now();
            }

            if ($order->status !== OrderStatusEnum::Opened->value && $order->status === OrderStatusEnum::Opened->value) {
                $validated['approved_at'] = null;
                $validated['canceled_at'] = null;
            }

            if (isset($validated['image'])) {
                if ($order->image && file_exists('storage/' . $order->image)) {
                    unlink('storage/' . $order->image);
                }

                if (!file_exists(public_path('storage/orders'))) {
                    mkdir(public_path('storage/orders'), 0777, true);
                }

                $fileName = 'orders/' . uniqid() . '.webp';

                if (in_array($validated['image']->getClientOriginalExtension(), ['jpg', 'jpeg', 'png'])) {
                    $file = Webp::make($validated['image']);

                    if ($file->save(public_path('storage/' . $fileName))) {
                        $validated['image'] = $fileName;
                    }
                } else {
                    $validated['image'] = $validated['image']->store('orders', 'public');
                }
            }

            $order->update($validated);

            DB::commit();

            Alert::toast('Pedido editado com sucesso.', 'success');
            return Redirect::route('orders.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            Alert::toast('Falha ao editar pedido.', 'error');
            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        try {
            $order->delete();
            Alert::toast('Pedido excluído com sucesso.', 'success');
            return Redirect::route('orders.index');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            Alert::toast('Falha ao excluir pedido.', 'error');
            return Redirect::route('orders.index');
        }
    }

    public function generatePaymentLink(Order $order)
    {
        MercadoPagoConfig::setAccessToken(env('MERCADOPAGO_ACCESS_TOKEN'));

        $client = new PreferenceClient();

        try {
            $preference = $client->create([
                "items" => [
                    [
                        "title" => $order->pack->title,
                        "quantity" => 1,
                        "currency_id" => "BRL",
                        "unit_price" => (float) number_format($order->value, 2, '.', ''),
                    ]
                ],
                "payment_methods" => [
                    "excluded_payment_types" => [
                        [
                            "id" => "ticket",
                        ],
                        [
                            "id" => "bank_transfer",
                        ],
                        [
                            "id" => "digital_currency",
                        ],
                    ],
                ],
                "external_reference" => $order->id,
                "notification_url" => route('webhook'),
            ]);
        } catch (MPApiException $e) {
            Log::error($e->getMessage());
            Alert::toast('Falha ao gerar link de pagamento.', 'error');
            return back()->withInput();
        }

        $order->update([
            'external_id' => $preference->id,
        ]);

        Log::info('Mercado Pago Preference', [$preference]);

        if (!app()->environment('local')) {
            return redirect($preference->init_point);
        } else {
            return redirect($preference->sandbox_init_point);
        }
    }

    /**
     * Endpoint for Pagseguro webhook.
     */
    public function paymentWebhook(Request $request)
    {
        Log::info('MercadoPago Webhook', $request->all());

        MercadoPagoConfig::setAccessToken(env('MERCADOPAGO_ACCESS_TOKEN'));

        if ($request->type !== 'payment') {
            return response()->json(null, 201);
        }

        $client = new PaymentClient();

        $payment = $client->get($request->data['id']);

        Log::info('MercadoPago Payment', [$payment]);

        $order = Order::where('external_id', $payment->external_reference)->first();

        if (!$order) {
            return response()->json(null, 201);
        }

        switch ($payment->status) {
            case 'approved':
                $order->status = OrderStatusEnum::Accomplished;
                $order->approved_at = now();
                $order->save();
                break;

            case 'cancelled':
                $order->status = OrderStatusEnum::Cancelled;
                $order->canceled_at = now();
                $order->save();
                break;

            default:
                break;
        }

        return response()->json(null, 201);
    }

    public function viewContract(Order $order)
    {
        return view('orders.contract', compact('order'));
    }

    public function signContract(Request $request, Order $order)
    {
        $filePath = 'contracts/' . uniqid() . '.jpg';

        list(, $fileData) = explode(';', $request->signature);
        list(, $fileData) = explode(',', $fileData);

        Storage::put("public/{$filePath}", base64_decode($fileData));

        $order->update([
            'contract_name' => $request->contract_name,
            'contract_cpf' => $request->contract_cpf,
            'contract_url' => $filePath,
            'contract_signed_at' => now(),
            'contract_ip' => $request->ip(),
        ]);

        Alert::toast('Contrato assinado com sucesso.', 'success');
        return Redirect::route('companies.index');
    }

    /**
     * Export the resources from storage to xlsx.
     */
    public function export()
    {
        return Excel::download(new OrderExport, 'vendas.xlsx');
    }
}
