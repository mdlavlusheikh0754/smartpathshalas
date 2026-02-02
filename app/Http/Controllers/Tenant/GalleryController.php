<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $galleries = Gallery::ordered()->get();
        return response()->json($galleries);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:photo,audio,video',
            'category' => 'nullable|string|max:100',
            'file' => 'required_if:type,photo,audio|file|mimes:jpeg,png,jpg,mp3,wav,ogg,aac|max:10240',
            'video_url' => 'required_if:type,video|nullable|url',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'category' => $request->category,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => true
        ];

        if ($request->type === 'video') {
            $data['video_url'] = $request->video_url;
        } else {
            $file = $request->file('file');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            if ($request->type === 'photo') {
                $path = $file->storeAs('website/gallery', $fileName, 'public');
                $data['file_path'] = $path;
            } else if ($request->type === 'audio') {
                $path = $file->storeAs('website/audio', $fileName, 'public');
                $data['file_path'] = $path;
            }
        }

        $gallery = Gallery::create($data);

        return response()->json([
            'success' => true,
            'message' => 'গ্যালারি আইটেম সফলভাবে যুক্ত করা হয়েছে',
            'data' => $gallery
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $gallery = Gallery::findOrFail($id);
        return response()->json($gallery);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $gallery = Gallery::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean'
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'sort_order' => $request->sort_order ?? $gallery->sort_order,
            'is_active' => $request->has('is_active') ? $request->is_active : $gallery->is_active
        ];

        $gallery->update($data);

        return response()->json([
            'success' => true,
            'message' => 'গ্যালারি আইটেম সফলভাবে আপডেট করা হয়েছে',
            'data' => $gallery
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gallery = Gallery::findOrFail($id);

        // Delete file from storage
        if ($gallery->file_path) {
            Storage::disk('public')->delete($gallery->file_path);
        }

        if ($gallery->thumbnail) {
            Storage::disk('public')->delete($gallery->thumbnail);
        }

        $gallery->delete();

        return response()->json([
            'success' => true,
            'message' => 'গ্যালারি আইটেম সফলভাবে মুছে ফেলা হয়েছে'
        ]);
    }

    /**
     * Update sort order
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:galleries,id',
            'items.*.sort_order' => 'required|integer|min:0'
        ]);

        foreach ($request->items as $item) {
            Gallery::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'সর্ট অর্ডার সফলভাবে আপডেট করা হয়েছে'
        ]);
    }
}
