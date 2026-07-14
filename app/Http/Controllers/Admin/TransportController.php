<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\Bus;
use App\Models\BusSeat;

class TransportController extends Controller
{
    public function index(Trip $trip)
    {
        $trip->load('buses.seats');

        return view('admin.trips.transport', compact('trip'));
    }

    public function store(Request $request, Trip $trip)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'rows'    => 'required|integer|min:1|max:50',
            'columns' => 'required|integer|min:1|max:10',
        ]);

        $totalSeats = $validated['rows'] * $validated['columns'];

        $validated['trip_id'] = $trip->id;
        $validated['total_seats'] = $totalSeats;

        $bus = Bus::create($validated);

        for ($row = 1; $row <= $bus->rows; $row++) {
            for ($col = 1; $col <= $bus->columns; $col++) {
                BusSeat::create([
                    'bus_id'      => $bus->id,
                    'seat_number' => $row . chr(64 + $col),
                    'row'         => $row,
                    'column'      => $col,
                    'is_occupied' => false,
                ]);
            }
        }

        return redirect()->route('admin.trips.transport.index', $trip)->with('success', 'Bus agregado correctamente.');
    }

    public function update(Request $request, Bus $bus)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'rows'    => 'required|integer|min:1|max:50',
            'columns' => 'required|integer|min:1|max:10',
        ]);

        $totalSeats = $validated['rows'] * $validated['columns'];
        $validated['total_seats'] = $totalSeats;

        $bus->update($validated);

        $existingIds = $bus->seats()->where('is_occupied', true)->pluck('id');

        $bus->seats()->delete();

        for ($row = 1; $row <= $bus->rows; $row++) {
            for ($col = 1; $col <= $bus->columns; $col++) {
                BusSeat::create([
                    'bus_id'      => $bus->id,
                    'seat_number' => $row . chr(64 + $col),
                    'row'         => $row,
                    'column'      => $col,
                    'is_occupied' => false,
                ]);
            }
        }

        return redirect()->route('admin.trips.transport.index', $bus->trip_id)->with('success', 'Bus actualizado correctamente.');
    }

    public function destroy(Bus $bus)
    {
        $tripId = $bus->trip_id;
        $bus->seats()->delete();
        $bus->delete();

        return redirect()->route('admin.trips.transport.index', $tripId)->with('success', 'Bus eliminado correctamente.');
    }

    public function seats(Bus $bus)
    {
        $bus->load('seats');

        return response()->json([
            'bus'   => $bus,
            'seats' => $bus->seats->groupBy('row'),
        ]);
    }
}
