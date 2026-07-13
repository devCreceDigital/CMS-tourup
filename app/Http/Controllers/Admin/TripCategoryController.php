<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TripCategory;
use Illuminate\Support\Str;

class TripCategoryController extends Controller
{
    public function index()
    {
        $categories = TripCategory::withCount('trips')->latest()->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:trip_categories,name',
            'description' => 'nullable|string',
            'icon'        => 'nullable|string|max:100',
            'is_active'   => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');

        TripCategory::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Categoría creada correctamente.');
    }

    public function edit($id)
    {
        $category = TripCategory::findOrFail($id);

        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = TripCategory::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:trip_categories,name,' . $id,
            'description' => 'nullable|string',
            'icon'        => 'nullable|string|max:100',
            'is_active'   => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy($id)
    {
        $category = TripCategory::findOrFail($id);

        if ($category->trips()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'No se puede eliminar la categoría porque tiene viajes asociados.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Categoría eliminada correctamente.');
    }
}
