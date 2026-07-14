<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\ProgramStatus;
use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\TripCategory;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $categoryStats = TripCategory::withCount('programs')->where('is_active', true)->get();

        $query = Program::with('category');

        if ($categoryId = $request->category) {
            $query->where('trip_category_id', $categoryId);
        }
        if ($request->status !== null) {
            $query->where('is_active', $request->boolean('status'));
        }
        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $programs = $query->latest()->paginate(15)->withQueryString();
        $viewMode = $request->view ?? 'table';

        return view('admin.programs.index', compact('programs', 'categoryStats', 'viewMode'));
    }

    public function create()
    {
        $categories = TripCategory::where('is_active', true)->get();

        return view('admin.programs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'trip_category_id' => 'required|exists:trip_categories,id',
            'description'      => 'nullable|string',
            'duration_days'    => 'required|integer|min:1',
            'age_range'        => 'nullable|string|max:50',
            'image'            => 'nullable|string|max:255',
            'is_active'        => 'boolean',
        ]);

        $validated['slug'] = generate_unique_slug(Program::class, $validated['name']);
        $validated['is_active'] = $request->boolean('is_active');

        Program::create($validated);

        return redirect()->route('admin.programs.index')->with('success', 'Programa creado correctamente.');
    }

    public function edit($id)
    {
        $program = Program::findOrFail($id);
        $categories = TripCategory::where('is_active', true)->get();

        return view('admin.programs.edit', compact('program', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $program = Program::findOrFail($id);

        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'trip_category_id' => 'required|exists:trip_categories,id',
            'description'      => 'nullable|string',
            'duration_days'    => 'required|integer|min:1',
            'age_range'        => 'nullable|string|max:50',
            'image'            => 'nullable|string|max:255',
            'is_active'        => 'boolean',
        ]);

        $validated['slug'] = generate_unique_slug(Program::class, $validated['name'], $id);
        $validated['is_active'] = $request->boolean('is_active');

        $program->update($validated);

        return redirect()->route('admin.programs.index')->with('success', 'Programa actualizado correctamente.');
    }

    public function destroy($id)
    {
        $program = Program::findOrFail($id);
        $program->delete();

        return redirect()->route('admin.programs.index')->with('success', 'Programa eliminado correctamente.');
    }

    public function duplicate($id)
    {
        $program = Program::findOrFail($id);

        $newProgram = $program->replicate();
        $newProgram->name = $program->name . ' (copia)';
        $newProgram->slug = generate_unique_slug(Program::class, $program->name . ' copia');
        $newProgram->save();

        return redirect()->route('admin.programs.edit', $newProgram->id)->with('success', 'Programa duplicado correctamente.');
    }
}
