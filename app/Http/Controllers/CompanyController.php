<?php

namespace App\Http\Controllers;

use App\Exports\CompanyExport;
use App\Http\Enums\OrderStatusEnum;
use App\Http\Enums\UserRoleEnum;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\App;
use App\Models\Category;
use App\Models\Client;
use App\Models\Company;
use App\Models\CompanyApp;
use App\Models\Tag;
use App\Models\User;
use Buglinjo\LaravelWebp\Webp;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $companies = Company::when($request->search, function ($query) use ($request) {
                $query->whereHas('client', function ($query) use ($request) {
                    $query->whereHas('user', function ($query) use ($request) {
                        $query->where('name', 'like', "%{$request->search}%");
                        $query->orWhere('email', 'like', "%{$request->search}%");
                    });
                });
                $query->orWhere('name', 'like', "%{$request->search}%");
                $query->orWhere('id', $request->search);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('status_sell'), function ($query) use ($request) {
                if ($request->status_sell) {
                    $query->whereHas('orders', function ($query) {
                        $query->where('status', 'approved');
                        $query->where('expire_at', '>', now());
                    });
                } else {
                    $query->whereDoesntHave('orders', function ($query) {
                        $query->where('status', 'approved');
                        $query->where('expire_at', '>', now());
                    });
                }
            })
            ->when($request->seller, function ($query) use ($request) {
                $query->where('user_id', $request->seller);
            })
            ->when($request->category, function ($query) use ($request) {
                $query->whereHas('categories', function ($query) use ($request) {
                    $query->where('category_id', $request->category);
                });
            })
            ->when($request->city, function ($query) use ($request) {
                $query->where('city', $request->city);
            })
            ->when($request->initial_created_at, function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->initial_created_at);
            })
            ->when($request->final_created_at, function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->final_created_at);
            })
            ->when($request->initial_expire_at, function ($query) use ($request) {
                $query->whereHas('lastOrderApproved', function ($query) use ($request) {
                    $query->whereDate('expire_at', '>=', $request->initial_expire_at);
                });
            })
            ->when($request->final_expire_at, function ($query) use ($request) {
                $query->whereHas('lastOrderApproved', function ($query) use ($request) {
                    $query->whereDate('expire_at', '<=', $request->final_expire_at);
                });
            })
            ->where(function ($query) {
                if (Auth::user()->role === UserRoleEnum::Seller->value) {
                    $query->where('user_id', Auth::id());
                } else if (Auth::user()->role === UserRoleEnum::Client->value) {
                    $client = Client::where('user_id', Auth::id())->first();
                    $query->where('client_id', $client->id);
                }
            })
            ->latest()
            ->paginate(50);

        $sellers = User::select()->role(UserRoleEnum::Seller->value)->get();
        $categories = Category::whereHas('companies')->where('status', true)->get();
        $cities = Company::distinct()->orderBy('city', 'asc')->pluck('city')->toArray();

        return view('companies.index', compact('companies', 'sellers', 'categories', 'cities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::where(function ($query) {
            if (Auth::user()->role === UserRoleEnum::Seller->value) {
                $query->where('seller_id', Auth::id());
            }
        })->get();
        $categories = Category::all();
        $apps = App::where('active', true)->get();
        $sellers = User::select()->role(UserRoleEnum::Seller->value)->get();

        $openingHours = "Segunda-feira: 08h às 18h\nTerça-feira: 08h às 18h\nQuarta-feira: 08h às 18h\nQuinta-feira: 08h às 18h\nSexta-feira: 08h às 18h\nSábado: Fechado\nDomingo e Feriado: Fechado";
        return view('companies.create', compact('clients', 'categories', 'apps', 'openingHours', 'sellers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();

            if (isset($validated['image'])) {
                if (!file_exists(public_path('storage/companies'))) {
                    mkdir(public_path('storage/companies'), 0777, true);
                }

                $fileName = 'companies/' . uniqid() . '.webp';

                if (in_array($validated['image']->getClientOriginalExtension(), ['jpg', 'jpeg', 'png'])) {
                    $file = Webp::make($validated['image']);

                    if ($file->save(public_path('storage/' . $fileName))) {
                        $validated['image'] = $fileName;
                    }
                } else {
                    $validated['image'] = $validated['image']->store('companies', 'public');
                }
            }

            if (isset($validated['banner'])) {
                if (!file_exists(public_path('storage/companies'))) {
                    mkdir(public_path('storage/companies'), 0777, true);
                }

                $fileName = 'companies/' . uniqid() . '.webp';

                if (in_array($validated['banner']->getClientOriginalExtension(), ['jpg', 'jpeg', 'png'])) {
                    $file = Webp::make($validated['banner']);

                    if ($file->save(public_path('storage/' . $fileName))) {
                        $validated['banner'] = $fileName;
                    }
                } else {
                    $validated['banner'] = $validated['banner']->store('companies', 'public');
                }
            }

            if (isset($validated['images']) && count($validated['images']) > 0) {
                $images = [];
                foreach ($validated['images'] as $image) {
                    $fileName = 'companies/' . uniqid() . '.webp';

                    if (in_array($image->getClientOriginalExtension(), ['jpg', 'jpeg', 'png'])) {
                        $file = Webp::make($image);

                        if ($file->save(public_path('storage/' . $fileName))) {
                            $images[] = $fileName;
                        }
                    } else {
                        $images[] = $image->store('companies', 'public');
                    }
                }
                $validated['images'] = $images;
            }

            $validated['user_id'] = $request->user()->role === UserRoleEnum::Seller->value ? $request->user()->id : $validated['user_id'];

            $validated['slug'] = Str::slug($validated['name']);

            $company = Company::create($validated);

            foreach ($validated['categories'] as $category) {
                $company->categories()->attach($category);
            }

            foreach ($validated['tags'] as $name) {
                $tag = Tag::updateOrCreate(['name' => $name], ['name' => $name]);
                $company->tags()->attach($tag);
            }

            foreach ($validated['apps'] as $app) {
                if (!isset($app['name']) || !isset($app['value'])) continue;

                CompanyApp::updateOrCreate([
                    'company_id' => $company->id,
                    'app_id' => $app['name'],
                ],[
                    'company_id' => $company->id,
                    'app_id' => $app['name'],
                    'url' => $app['value'],
                ]);
            }

            DB::commit();

            Alert::toast('Empresa cadastrada com sucesso.', 'success');
            return Redirect::route('companies.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            Alert::toast('Falha ao cadastrar empresa.', 'error');
            return back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        $clients = Client::where(function ($query) {
            if (Auth::user()->role === UserRoleEnum::Seller->value) {
                $query->where('seller_id', Auth::id());
            }
        })->get();
        $categories = Category::all();
        $apps = App::where('active', true)->get();
        $sellers = User::select()->role(UserRoleEnum::Seller->value)->get();

        return view('companies.edit', compact('company', 'clients', 'categories', 'apps', 'sellers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();

            if (isset($validated['image'])) {
                if ($company->image && file_exists('storage/' . $company->image)) {
                    unlink('storage/' . $company->image);
                }

                if (!file_exists(public_path('storage/companies'))) {
                    mkdir(public_path('storage/companies'), 0777, true);
                }

                $fileName = 'companies/' . uniqid() . '.webp';

                if (in_array($validated['image']->getClientOriginalExtension(), ['jpg', 'jpeg', 'png'])) {
                    $file = Webp::make($validated['image']);

                    if ($file->save(public_path('storage/' . $fileName))) {
                        $validated['image'] = $fileName;
                    }
                } else {
                    $validated['image'] = $validated['image']->store('companies', 'public');
                }
            }

            if (isset($validated['banner'])) {
                if ($company->banner && file_exists('storage/' . $company->banner)) {
                    unlink('storage/' . $company->banner);
                }

                if (!file_exists(public_path('storage/companies'))) {
                    mkdir(public_path('storage/companies'), 0777, true);
                }

                $fileName = 'companies/' . uniqid() . '.webp';

                if (in_array($validated['banner']->getClientOriginalExtension(), ['jpg', 'jpeg', 'png'])) {
                    $file = Webp::make($validated['banner']);

                    if ($file->save(public_path('storage/' . $fileName))) {
                        $validated['banner'] = $fileName;
                    }
                } else {
                    $validated['banner'] = $validated['banner']->store('companies', 'public');
                }
            }

            if (isset($validated['images']) && count($validated['images']) > 0) {
                if ($company->images) {
                    foreach ($company->images as $image) {
                        if (!file_exists('storage/' . $image)) continue;
                        unlink('storage/' . $image);
                    }
                }
                $images = [];
                foreach ($validated['images'] as $image) {
                    $fileName = 'companies/' . uniqid() . '.webp';

                    if (in_array($image->getClientOriginalExtension(), ['jpg', 'jpeg', 'png'])) {
                        $file = Webp::make($image);

                        if ($file->save(public_path('storage/' . $fileName))) {
                            $images[] = $fileName;
                        }
                    } else {
                        $images[] = $image->store('companies', 'public');
                    }
                }
                $validated['images'] = $images;
            }

            $validated['slug'] = Str::slug($validated['name']);

            $company->update($validated);

            $company->children()->update([
                'image' => $company->image,
                'banner' => $company->banner,
                'images' => $company->images,
            ]);

            $company->lastOrderApproved()->update([
                'expire_at' => $validated['expire_at'],
            ]);

            $company->categories()->detach();
            foreach ($validated['categories'] as $category) {
                $company->categories()->attach($category);
            }

            $company->tags()->detach();
            foreach ($validated['tags'] as $name) {
                $tag = Tag::updateOrCreate(['name' => $name], ['name' => $name]);
                $company->tags()->attach($tag);
            }

            if (isset($validated['apps'])) {
                $company->companyApps()->delete();
                foreach ($validated['apps'] as $app) {
                    if (!isset($app['name']) || !isset($app['value'])) continue;

                    CompanyApp::updateOrCreate([
                        'company_id' => $company->id,
                        'app_id' => $app['name'],
                    ],[
                        'company_id' => $company->id,
                        'app_id' => $app['name'],
                        'url' => $app['value'],
                    ]);
                }
            }

            DB::commit();

            Alert::toast('Empresa editada com sucesso.', 'success');
            return Redirect::route('companies.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            Alert::toast('Falha ao editar empresa.', 'error');
            return back()->withInput()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $company->delete();
        Alert::toast('Empresa deletada com sucesso.', 'success');
        return back();
    }

    /**
     * Export the resources from storage to xlsx.
     */
    public function export()
    {
        return Excel::download(new CompanyExport, 'empresas.xlsx');
    }

    /**
     * Duplicate the specified resource from storage.
     */
    public function duplicate(Company $company)
    {
        $newCompany = $company->replicate();
        $newCompany->name = $company->name . ' (Cópia)';
        $newCompany->parent_id = $company->id;
        $newCompany->slug = Str::slug($company->name) . '-' . Str::random(5);
        $newCompany->save();

        $newCompany->categories()->attach($company->categories->pluck('id')->toArray());
        $newCompany->tags()->attach($company->tags->pluck('id')->toArray());

        foreach ($company->companyApps as $app) {
            CompanyApp::updateOrCreate([
                'company_id' => $newCompany->id,
                'app_id' => $app->app_id,
            ],[
                'url' => $app->url,
            ]);
        }

        Alert::toast('Empresa duplicada com sucesso.', 'success');
        return Redirect::route('companies.index');
    }

}
