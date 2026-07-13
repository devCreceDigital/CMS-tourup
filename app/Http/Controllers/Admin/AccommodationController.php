<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\Room;
use App\Models\Traveler;
use App\Models\Trip;
use Illuminate\Http\Request;

class AccommodationController extends Controller
{
    public function index(Trip $trip)
    {
        $trip->load('bookings.traveler');
        $accommodations = $trip->accommodations()->with('rooms.travelers')->get();
        return view('admin.accommodations.index', compact('trip', 'accommodations'));
    }

    public function store(Request $request, Trip $trip)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'stars' => 'nullable|integer|min:1|max:5',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'link' => 'nullable|url|max:500',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('accommodations', 'public');
        }

        $trip->accommodations()->create($data);

        return redirect()->route('admin.trips.accommodations.index', $trip)
            ->with('success', 'Alojamiento añadido correctamente.');
    }

    public function update(Request $request, Accommodation $accommodation)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'stars' => 'nullable|integer|min:1|max:5',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'link' => 'nullable|url|max:500',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('accommodations', 'public');
        }

        $accommodation->update($data);

        return redirect()->route('admin.trips.accommodations.index', $accommodation->trip_id)
            ->with('success', 'Alojamiento actualizado.');
    }

    public function destroy(Accommodation $accommodation)
    {
        $tripId = $accommodation->trip_id;
        $accommodation->delete();

        return redirect()->route('admin.trips.accommodations.index', $tripId)
            ->with('success', 'Alojamiento eliminado.');
    }

    public function storeRoom(Request $request, Accommodation $accommodation)
    {
        $data = $request->validate([
            'room_number' => 'required|string|max:50',
            'type' => 'required|string|max:100',
            'capacity' => 'required|integer|min:1|max:20',
        ]);

        $accommodation->rooms()->create($data);

        return redirect()->route('admin.trips.accommodations.index', $accommodation->trip_id)
            ->with('success', 'Habitación añadida.');
    }

    public function assignTraveler(Request $request, Room $room)
    {
        $data = $request->validate([
            'traveler_id' => 'required|exists:travelers,id',
        ]);

        $currentCount = $room->travelers()->count();
        if ($currentCount >= $room->capacity) {
            return back()->with('error', 'La habitación ha alcanzado su capacidad máxima.');
        }

        $room->travelers()->syncWithoutDetaching([$data['traveler_id']]);

        return back()->with('success', 'Viajero asignado a la habitación.');
    }

    public function removeTraveler(Room $room, Traveler $traveler)
    {
        $room->travelers()->detach($traveler->id);

        return back()->with('success', 'Viajero removido de la habitación.');
    }
}
