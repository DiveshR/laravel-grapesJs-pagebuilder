<?php

namespace App\Http\Controllers;

use App\Models\Blog; // Import Blog model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaUploadController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        // Let's fetch all media from the 'blog_images' collection of any Blog model for now
        // This needs refinement: ideally, only show images relevant to the current context or user
        $mediaItems = Media::where('collection_name', 'blog_images')->orderBy('created_at', 'desc')->get();

        $assets = $mediaItems->map(function (Media $mediaItem) {
            return [
                'id' => $mediaItem->id,
                'src' => $mediaItem->getFullUrl(),
                'thumb_src' => $mediaItem->getFullUrl('thumb'),
                'type' => 'image',
                'category' => 'Blog Images',
                'name' => $mediaItem->name,
                'file_name' => $mediaItem->file_name,
                'created_at' => $mediaItem->created_at->toFormattedDateString(),
            ];
        });

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($assets);
        }
        return view('media.index', ['mediaAssets' => $assets]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'blog_id' => 'sometimes|nullable|exists:blogs,id' // Optional blog_id
        ]);

        $urls = [];
        $user = Auth::user();
        $blog = $request->filled('blog_id') ? Blog::find($request->input('blog_id')) : null;

        foreach ($request->file('files') as $file) {
            $mediaAdder = $blog ? $blog->addMedia($file) : $user->addMedia($file);
            $collectionName = $blog ? 'blog_images' : 'user_temp_images'; // Different collections
            
            $mediaItem = $mediaAdder->toMediaCollection($collectionName);
            $urls[] = $mediaItem->getFullUrl();
        }

        $response_data = array_map(function($url) {
            return ['src' => $url];
        }, $urls);

        return response()->json(['data' => $response_data]);
    }
}
