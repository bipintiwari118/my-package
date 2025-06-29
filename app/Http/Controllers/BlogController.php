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
            'featured_image' =>'nullable',
            'description' => 'nullable',
            'gallery_images'=>'nullable',
        ]);

        Blog::create([
            'title' => $request->title,
        'featured_image' => json_decode($request->featured_image, true),
        'description' => $request->description,
        'gallery_images' => is_array($request->gallery_images)
                            ? $request->gallery_images
                            : json_decode($request->gallery_images, true),
        ]);

        return redirect()->route('blog.list')->with('success', 'Blog created!');
    }

    public function list(){
        $blogs=Blog::latest()->paginate(10);
        return view('admin.blog.list',compact('blogs'));
    }
}
