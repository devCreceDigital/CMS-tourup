<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\ItineraryDay;
use App\Models\ItineraryActivity;

class ItineraryController extends Controller
{
    public function index(Trip $trip)
    {
        $trip->load('itineraryDays.activities');

        $selectedDay = null;
        if ($dayId = request('day')) {
            $selectedDay = $trip->itineraryDays->find($dayId);
        }

        return view('admin.trips.itinerary', compact('trip', 'selectedDay'));
    }

    public function storeDay(Request $request, Trip $trip)
    {
        $validated = $request->validate([
            'day_number' => 'required|integer|min:1',
            'date'       => 'required|date',
            'title'      => 'required|string|max:255',
        ]);

        $validated['trip_id'] = $trip->id;

        ItineraryDay::create($validated);

        return redirect()->route('admin.trips.itinerary.index', $trip)->with('success', 'Día agregado correctamente.');
    }

    public function updateDay(Request $request, ItineraryDay $day)
    {
        $validated = $request->validate([
            'day_number' => 'required|integer|min:1',
            'date'       => 'required|date',
            'title'      => 'required|string|max:255',
        ]);

        $day->update($validated);

        return redirect()->route('admin.trips.itinerary.index', $day->trip_id)->with('success', 'Día actualizado correctamente.');
    }

    public function destroyDay(ItineraryDay $day)
    {
        $tripId = $day->trip_id;
        $day->delete();

        return redirect()->route('admin.trips.itinerary.index', $tripId)->with('success', 'Día eliminado correctamente.');
    }

    public function togglePublish(ItineraryDay $day)
    {
        $day->update(['is_published' => !$day->is_published]);

        return back()->with('success', $day->is_published ? 'Día publicado.' : 'Día archivado.');
    }

    public function storeActivity(Request $request, ItineraryDay $day)
    {
        $validated = $request->validate([
            'time'           => 'nullable|string|max:20',
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'type'           => 'nullable|string|max:50',
            'important_notes'=> 'nullable|string',
            'order'          => 'nullable|integer|min:0',
        ]);

        $validated['itinerary_day_id'] = $day->id;

        ItineraryActivity::create($validated);

        return redirect()->route('admin.trips.itinerary.index', $day->trip_id)->with('success', 'Actividad agregada correctamente.');
    }

    public function updateActivity(Request $request, ItineraryActivity $activity)
    {
        $validated = $request->validate([
            'time'           => 'nullable|string|max:20',
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'type'           => 'nullable|string|max:50',
            'important_notes'=> 'nullable|string',
            'order'          => 'nullable|integer|min:0',
        ]);

        $activity->update($validated);

        return redirect()->route('admin.trips.itinerary.index', $activity->day->trip_id)->with('success', 'Actividad actualizada correctamente.');
    }

    public function destroyActivity(ItineraryActivity $activity)
    {
        $tripId = $activity->day->trip_id;
        $activity->delete();

        return redirect()->route('admin.trips.itinerary.index', $tripId)->with('success', 'Actividad eliminada correctamente.');
    }
}
