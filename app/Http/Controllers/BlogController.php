<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;

class BlogController extends Controller
{
    public function create(){
        return view('admin.blog.add');
    }

      public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'featured_image' => 'nullable|string',
            'description' => 'nullable'
        ]);

        Blog::create([
            'title' => $request->title,
            'featured_image' => $request->featured_image,
            'description' => $request->description,
            'gallery_images' => $request->gallery_images
        ]);

        return redirect()->route('blog.create')->with('success', 'Blog created!');
    }

    public function list(){
        $blogs=Blog::latest()->paginate(10);
        return view('admin.blog.list',compact())
    }
}
