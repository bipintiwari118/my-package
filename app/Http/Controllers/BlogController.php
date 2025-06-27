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
            'description' => 'required'
        ]);

        Blog::create([
            'title' => $request->title,
            'featured_image' => $request->featured_image,
            'description' => $request->description
        ]);

        return redirect()->route('blog.create')->with('success', 'Blog created!');
    }
}
