<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\Tag;
use Buglinjo\LaravelWebp\Webp;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use RealRashid\SweetAlert\Facades\Alert;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $posts = Post::latest()->paginate(50);
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();

            $webp = Webp::make($validated['image']);
            $fileName = 'posts/' . uniqid() . '.webp';

            if (!file_exists(public_path('storage/posts'))) {
                mkdir(public_path('storage/posts'), 0777, true);
            }

            if ($webp->save(public_path('storage/' . $fileName))) {
                $validated['image'] = $fileName;
            }

            $post = Post::create($validated);

            foreach ($validated['tags'] as $name) {
                $tag = Tag::updateOrCreate(['name' => $name], ['name' => $name]);
                $post->tags()->attach($tag);
            }

            DB::commit();

            Alert::toast('Post cadastrado com sucesso.', 'success');
            return Redirect::route('posts.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            Alert::toast('Falha ao cadastrar post.', 'error');
            return back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();

            if (isset($validated['image'])) {
                if ($post->image && file_exists('storage/' . $post->image)) {
                    unlink('storage/' . $post->image);
                }

                $webp = Webp::make($validated['image']);
                $fileName = 'posts/' . uniqid() . '.webp';

                if (!file_exists(public_path('storage/posts'))) {
                    mkdir(public_path('storage/posts'), 0777, true);
                }

                if ($webp->save(public_path('storage/' . $fileName))) {
                    $validated['image'] = $fileName;
                }
            }

            $post->update($validated);

            $post->tags()->detach();
            foreach ($validated['tags'] as $name) {
                $tag = Tag::updateOrCreate(['name' => $name], ['name' => $name]);
                $post->tags()->attach($tag);
            }

            DB::commit();

            Alert::toast('Post editado com sucesso.', 'success');
            return Redirect::route('posts.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            Alert::toast('Falha ao editar post.', 'error');
            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        Alert::toast('Post deletado com sucesso.', 'success');
        return back();
    }
}
