<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        return Inertia::render('Posts/Index', [
            'posts' => Post::orderBy('created_at', 'desc')->with(['user', 'likes'])->get()->map(function ($post) {
                $post['isPostLikedByCurrentUser'] = $post->likedBy(Auth::user());
                return $post;
            }),
        ]);
    }

    public function show(Post $post)
    {
        //
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'body' => 'required'
        ]);

        $request->user()->posts()->create($validated);

        return redirect(route('posts'));
    }

    public function edit(Post $post)
    {
        //
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        Gate::authorize('update', $post);

        $validated = $request->validate([
            'body' => 'required'
        ]);

        $post->update($validated);

        return redirect(route('posts'));
    }

    public function destroy(Post $post): RedirectResponse
    {
        Gate::authorize('delete', $post);
        $post->delete();
        return redirect(route('posts'));
    }
}
