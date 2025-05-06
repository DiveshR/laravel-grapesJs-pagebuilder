<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaUploadController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        // Fetch media items for the user, e.g., from the 'images' collection
        // You can also fetch all media regardless of user or model:
        // $mediaItems = Media::all(); 
        // Or filter by a specific collection if you organize them that way:
        // $mediaItems = Media::where('collection_name', 'images')->get();

        // For now, let's get all media from the 'images' collection of the current user
        $mediaItems = $user->getMedia('images');

        $assets = $mediaItems->map(function (Media $mediaItem) {
            return [
                'id' => $mediaItem->id,
                'src' => $mediaItem->getFullUrl(),
                'thumb_src' => $mediaItem->getFullUrl(), // Assuming you might have a 'thumb' conversion
                'type' => 'image',
                'category' => 'Images',
                'name' => $mediaItem->name,
                'file_name' => $mediaItem->file_name,
                'created_at' => $mediaItem->created_at->toFormattedDateString(),
            ];
        });

        // If GrapesJS (or any AJAX client) explicitly asks for JSON
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($assets); // For GrapesJS Asset Manager
        }

        // For regular browser requests, return a view
        return view('media.index', ['mediaAssets' => $assets]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'files' => 'required|array', // GrapesJS sends files as an array, even if it's one
            'files.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate each file
        ]);

        $urls = [];
        $user = Auth::user(); // Get the authenticated user

        foreach ($request->file('files') as $file) {
            // Add file to user's default media collection. 
            // You can specify a disk if you have configured one in config/media-library.php
            $mediaItem = $user->addMedia($file)->toMediaCollection('images'); 
            $urls[] = $mediaItem->getFullUrl(); // Or $mediaItem->getUrl() depending on your setup
        }

        // GrapesJS expects an array of objects with a `src` property, or just an array of URLs.
        // Let's return an array of objects for more flexibility.
        $response_data = array_map(function($url) {
            return ['src' => $url];
        }, $urls);

        return response()->json(['data' => $response_data]);
    }
}
