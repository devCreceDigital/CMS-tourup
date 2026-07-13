<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\TripCategory;
use Illuminate\Support\Str;

class TripController extends Controller
{
    public function index(Request $request)
    {
        $query = Trip::with('category');

        if ($request->filled('year')) {
            $query->whereYear('start_date', $request->year);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('reference', 'like', '%' . $request->search . '%');
        }

        $trips = $query->latest()->paginate(15);

        return view('admin.trips.index', compact('trips'));
    }

    public function show($id)
    {
        $trip = Trip::with(['category', 'pricingGroups.installments'])->findOrFail($id);

        return view('admin.trips.show', compact('trip'));
    }

    public function create()
    {
        $categories = TripCategory::where('is_active', true)->get();

        return view('admin.trips.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reference'        => 'required|string|max:50|unique:trips,reference',
            'name'             => 'required|string|max:255',
            'trip_category_id' => 'required|exists:trip_categories,id',
            'destination'      => 'required|string|max:255',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after_or_equal:start_date',
            'description'      => 'nullable|string',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'status'           => 'required|in:active,inactive,completed,cancelled',
            'total_spots'      => 'required|integer|min:1',
            'occupied_spots'   => 'required|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('trips', 'public');
        }

        Trip::create($validated);

        return redirect()->route('admin.trips.index')->with('success', 'Viaje creado correctamente.');
    }

    public function edit($id)
    {
        $trip = Trip::findOrFail($id);
        $categories = TripCategory::where('is_active', true)->get();

        return view('admin.trips.edit', compact('trip', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $trip = Trip::findOrFail($id);

        $validated = $request->validate([
            'reference'        => 'required|string|max:50|unique:trips,reference,' . $id,
            'name'             => 'required|string|max:255',
            'trip_category_id' => 'required|exists:trip_categories,id',
            'destination'      => 'required|string|max:255',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after_or_equal:start_date',
            'description'      => 'nullable|string',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'status'           => 'required|in:active,inactive,completed,cancelled',
            'total_spots'      => 'required|integer|min:1',
            'occupied_spots'   => 'required|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('trips', 'public');
        }

        $trip->update($validated);

        return redirect()->route('admin.trips.index')->with('success', 'Viaje actualizado correctamente.');
    }

    public function destroy($id)
    {
        $trip = Trip::findOrFail($id);
        $trip->delete();

        return redirect()->route('admin.trips.index')->with('success', 'Viaje eliminado correctamente.');
    }

    public function duplicate($id)
    {
        $trip = Trip::findOrFail($id);

        $newTrip = $trip->replicate();
        $newTrip->reference = $trip->reference . '-copy';
        $newTrip->slug = $trip->slug . '-copy';
        $newTrip->name = $trip->name . ' (copia)';
        $newTrip->occupied_spots = 0;
        $newTrip->save();

        return redirect()->route('admin.trips.edit', $newTrip->id)->with('success', 'Viaje duplicado correctamente.');
    }
}
