<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->paginate(10);
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'body'      => 'required|string',
            'category'  => 'required|string|max:100',
            'published' => 'boolean',
        ]);

        $validated['published'] = $request->boolean('published');

        Post::create($validated);

        return redirect()->route('posts.index')->with('success', '投稿を作成しました。');
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'body'      => 'required|string',
            'category'  => 'required|string|max:100',
            'published' => 'boolean',
        ]);

        $validated['published'] = $request->boolean('published');

        $post->update($validated);

        return redirect()->route('posts.show', $post)->with('success', '投稿を更新しました。');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('posts.index')->with('success', '投稿を削除しました。');
    }
}
