<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HeroMediaController extends Controller
{
    public function index()
    {
        $media = \App\Models\HeroMedia::orderBy('sorting_order')->get();
        return view('admin.hero-media.index', compact('media'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpeg,png,jpg,webp,mp4,webm|max:20480',
        ]);

        $file = $request->file('file');
        $mime = $file->getMimeType();
        $type = str_starts_with($mime, 'video') ? 'video' : 'image';

        $path = $file->store('hero_media', 'public');

        $maxOrder = \App\Models\HeroMedia::max('sorting_order') ?? 0;

        \App\Models\HeroMedia::create([
            'type' => $type,
            'file_path' => $path,
            'sorting_order' => $maxOrder + 1,
            'is_active' => true,
        ]);

        return back()->with('success', 'Media añadida correctamente.');
    }

    public function destroy(\App\Models\HeroMedia $heroMedia)
    {
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($heroMedia->file_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($heroMedia->file_path);
        }
        $heroMedia->delete();
        return back()->with('success', 'Media eliminada correctamente.');
    }

    public function toggleActive(\App\Models\HeroMedia $heroMedia)
    {
        $heroMedia->update(['is_active' => !$heroMedia->is_active]);
        return back()->with('success', 'Estado modificado correctamente.');
    }

    public function reorder(\Illuminate\Http\Request $request)
    {
        $order = $request->input('order');
        if (is_array($order)) {
            foreach ($order as $index => $id) {
                \App\Models\HeroMedia::where('id', $id)->update(['sorting_order' => $index]);
            }
        }
        return response()->json(['success' => true]);
    }
}
