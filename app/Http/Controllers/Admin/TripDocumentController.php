<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\TripBooking;
use App\Models\TravelerDocument;
use Illuminate\Http\Request;

class TripDocumentController extends Controller
{
    public function index(Trip $trip)
    {
        $trip->load('bookings.traveler');

        $documents = TravelerDocument::where('trip_id', $trip->id)
            ->with('traveler')
            ->get()
            ->groupBy('traveler_id');

        return view('admin.trips.documents', compact('trip', 'documents'));
    }

    protected function syncDocStatus(Trip $trip, int $travelerId): void
    {
        $allDocs = TravelerDocument::where('trip_id', $trip->id)
            ->where('traveler_id', $travelerId)
            ->get();

        if ($allDocs->isEmpty()) {
            return;
        }

        $hasRejected = $allDocs->contains(fn($d) => $d->status === 'rejected');
        if ($hasRejected) {
            TripBooking::where('trip_id', $trip->id)
                ->where('traveler_id', $travelerId)
                ->update(['document_status' => 'rejected']);
            return;
        }

        $allComplete = $allDocs->every(fn($d) => $d->status === 'complete');
        $newStatus = $allComplete ? 'complete' : 'pending';

        TripBooking::where('trip_id', $trip->id)
            ->where('traveler_id', $travelerId)
            ->update(['document_status' => $newStatus]);
    }

    public function store(Request $request, Trip $trip)
    {
        $validated = $request->validate([
            'traveler_id' => 'required|exists:travelers,id',
            'type' => 'required|string|max:50',
            'label' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:20480',
            'status' => 'required|in:pending,in_review,complete,rejected',
        ]);

        $data = [
            'trip_id' => $trip->id,
            'traveler_id' => $validated['traveler_id'],
            'type' => $validated['type'],
            'label' => $validated['label'],
            'status' => $validated['status'],
        ];

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('documents', 'public');
        }

        TravelerDocument::updateOrCreate(
            ['trip_id' => $trip->id, 'traveler_id' => $validated['traveler_id'], 'type' => $validated['type']],
            $data
        );

        $this->syncDocStatus($trip, $validated['traveler_id']);

        return redirect()->route('admin.trips.documents', $trip)->with('success', 'Documento registrado correctamente.');
    }

    public function update(Request $request, Trip $trip, TravelerDocument $document)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_review,complete,rejected',
            'notes' => 'nullable|string',
        ]);

        $document->update($validated);

        $this->syncDocStatus($trip, $document->traveler_id);

        return redirect()->route('admin.trips.documents', $trip)->with('success', 'Estado del documento actualizado.');
    }

    public function destroy(Trip $trip, TravelerDocument $document)
    {
        $document->delete();

        return redirect()->route('admin.trips.documents', $trip)->with('success', 'Documento eliminado.');
    }
}
