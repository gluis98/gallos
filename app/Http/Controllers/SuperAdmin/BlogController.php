<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $posts = BlogPost::with('author')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('super-admin.blog.index', compact('posts'));
    }

    public function create(): View
    {
        return view('super-admin.blog.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:blog_posts,slug',
            'excerpt'          => 'nullable|string|max:500',
            'content'          => 'required|string',
            'meta_title'       => 'nullable|string|max:160',
            'meta_description' => 'nullable|string|max:320',
            'keywords'         => 'nullable|string',
            'featured_image'   => 'nullable|url|max:500',
            'category'         => 'nullable|string|max:80',
            'status'           => 'required|in:draft,published',
            'published_at'     => 'nullable|date',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $data['author_id'] = auth()->id();

        BlogPost::create($data);

        return redirect()->route('superadmin.blog.index')
            ->with('success', 'Artículo creado correctamente.');
    }

    public function edit(BlogPost $blog): View
    {
        return view('super-admin.blog.edit', ['post' => $blog]);
    }

    public function update(Request $request, BlogPost $blog): RedirectResponse
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:blog_posts,slug,' . $blog->id,
            'excerpt'          => 'nullable|string|max:500',
            'content'          => 'required|string',
            'meta_title'       => 'nullable|string|max:160',
            'meta_description' => 'nullable|string|max:320',
            'keywords'         => 'nullable|string',
            'featured_image'   => 'nullable|url|max:500',
            'category'         => 'nullable|string|max:80',
            'status'           => 'required|in:draft,published',
            'published_at'     => 'nullable|date',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = $blog->published_at ?? now();
        }

        $blog->update($data);

        return redirect()->route('superadmin.blog.index')
            ->with('success', 'Artículo actualizado correctamente.');
    }

    public function destroy(BlogPost $blog): RedirectResponse
    {
        $blog->delete();

        return redirect()->route('superadmin.blog.index')
            ->with('success', 'Artículo eliminado.');
    }
}
