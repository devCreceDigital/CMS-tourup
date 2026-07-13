<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function index()
    {
        $sections = [
            'hero' => 'Hero y Portadas',
            'about' => 'Sobre Nosotros',
            'blog' => 'Blog',
            'services' => 'Servicios',
            'contact' => 'Contacto',
            'general' => 'General',
        ];

        $images = MediaImage::orderBy('section')->orderBy('key')->get()->groupBy('section');

        return view('admin.images.index', compact('images', 'sections'));
    }

    public function upload(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:100|unique:media_images,key',
            'section' => 'required|string|max:50',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'alt_text' => 'nullable|string|max:255',
        ]);

        $path = $request->file('image')->store('media-images', 'public');

        MediaImage::create([
            'key' => $validated['key'],
            'file_path' => $path,
            'alt_text' => $validated['alt_text'] ?? '',
            'section' => $validated['section'],
        ]);

        return redirect()->route('admin.images.index')->with('success', 'Imagen subida correctamente.');
    }

    public function destroy(MediaImage $image)
    {
        Storage::disk('public')->delete($image->file_path);
        $image->delete();

        return redirect()->route('admin.images.index')->with('success', 'Imagen eliminada correctamente.');
    }
}
