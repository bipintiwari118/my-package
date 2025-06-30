<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Media;

class MediaController extends Controller
{
    public function modal()
{
    $media = Media::latest()->paginate(10);
    return view('admin.modal', compact('media'));
}


public function upload(Request $request)
{
    $request->validate([
        'file' => 'required|file|max:5120',
          'alt' => 'nullable|string|max:255',
        'title' => 'nullable|string|max:255',
        'description' => 'nullable|string',
    ]);

    $file = $request->file('file');
    $path = $file->store('public/media');

    Media::create([
        'file_name' => basename($path),
        'original_name' => $file->getClientOriginalName(),
        'mime_type' => $file->getMimeType(),
        'extension' => $file->getClientOriginalExtension(),
        'size' => $file->getSize(),
        'url' => Storage::url($path),
        'alt' => $request->alt,
        'title' => $request->title,
        'description' => $request->description,
    ]);

    return redirect()->back();
}

public function destroy(Media $media)
{
    Storage::delete('public/media/' . $media->file_name);
    $media->delete();
    return redirect()->back();
}

public function update(Request $request, $id)
{
    $request->validate([
        'alt' => 'nullable|string',
        'title' => 'nullable|string',
        'description' => 'nullable|string',
    ]);

    $media = Media::findOrFail($id);
    $media->alt = $request->alt;
    $media->title = $request->title;
    $media->description = $request->description;
    $media->save();

    return redirect()->back()->with('success', 'Media metadata updated!');
}

}
