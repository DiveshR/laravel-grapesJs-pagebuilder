<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BlogController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Blog $blogs)
    {
        $blogs = $blogs->with('user:id,name')->paginate(5);
        return view('blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Blog::class); // Ensure user can create
        return view('blogs.create'); // Assuming you have a create.blade.php
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Blog::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string', // GrapesJS content will be a JSON string
        ]);

        $request->user()->blogs()->create($validated);

        return redirect()->route('blogs.index')->with('success', 'Blog post created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        $this->authorize('view', $blog);
        // You'll need to parse $blog->content (JSON) if you want to display GrapesJS HTML/CSS
        // For example, in the view: $content = json_decode($blog->content, true);
        return view('blogs.show', compact('blog')); // Assuming you have a show.blade.php
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {
        return view('blogs.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string', // GrapesJS content is a JSON string
        ]);
        $blog->update($validated);
        // return $blog;

        return redirect()->route('blogs.index')->with('success', 'Blog post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        $this->authorize('delete', $blog);
        $blog->delete();
        return redirect()->route('blogs.index')->with('success', 'Blog post deleted successfully!');
    }
}
