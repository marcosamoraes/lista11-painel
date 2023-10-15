<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppRequest;
use App\Http\Requests\UpdateAppRequest;
use App\Models\App;
use Buglinjo\LaravelWebp\Webp;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use RealRashid\SweetAlert\Facades\Alert;

class AppController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $apps = App::latest()->paginate(50);
        return view('apps.index', compact('apps'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('apps.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAppRequest $request)
    {
        try {
            $validated = $request->validated();

            $webp = Webp::make($validated['image']);
            $fileName = 'apps/' . uniqid() . '.webp';

            if (!file_exists(public_path('storage/apps'))) {
                mkdir(public_path('storage/apps'), 0777, true);
            }

            if ($webp->save(public_path('storage/' . $fileName))) {
                $validated['image'] = $fileName;
            }

            App::create($validated);
            Alert::toast('App cadastrado com sucesso.', 'success');
            return Redirect::route('apps.index');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            Alert::toast('Falha ao cadastrar app.', 'error');
            return back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(App $app)
    {
        return view('apps.edit', compact('app'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAppRequest $request, App $app)
    {
        try {
            $validated = $request->validated();

            if (isset($validated['image'])) {
                if ($app->image && file_exists('storage/' . $app->image)) {
                    unlink('storage/' . $app->image);
                }

                $webp = Webp::make($validated['image']);
                $fileName = 'apps/' . uniqid() . '.webp';

                if (!file_exists(public_path('storage/apps'))) {
                    mkdir(public_path('storage/apps'), 0777, true);
                }

                if ($webp->save(public_path('storage/' . $fileName))) {
                    $validated['image'] = $fileName;
                }
            }

            $app->update($validated);
            Alert::toast('App editado com sucesso.', 'success');
            return Redirect::route('apps.index');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            Alert::toast('Falha ao editar app.', 'error');
            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(App $app)
    {
        $app->delete();
        Alert::toast('App deletado com sucesso.', 'success');
        return back();
    }
}
