<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        return view('admin.calendar.index');
    }

    public function data(Request $request)
    {
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        $trips = Trip::whereYear('start_date', $year)
            ->orWhereYear('end_date', $year)
            ->orWhere(function ($q) use ($year) {
                $q->where('start_date', '<=', "{$year}-12-31")
                    ->where('end_date', '>=', "{$year}-01-01");
            })
            ->get(['id', 'name', 'reference', 'start_date', 'end_date', 'status', 'total_spots', 'occupied_spots']);

        $events = [];

        foreach ($trips as $trip) {
            $start = \Carbon\Carbon::parse($trip->start_date);
            $end = \Carbon\Carbon::parse($trip->end_date);

            $color = match ($trip->status) {
                'active' => '#10b981',
                'on_sale' => '#3b82f6',
                'completed' => '#6b7280',
                'cancelled' => '#ef4444',
                'inactive' => '#d1d5db',
                default => '#9ca3af',
            };

            $events[] = [
                'id' => $trip->id,
                'title' => $trip->name,
                'reference' => $trip->reference,
                'start' => $start->format('Y-m-d'),
                'end' => $end->copy()->addDay()->format('Y-m-d'),
                'color' => $color,
                'status' => $trip->status,
                'spots' => ($trip->occupied_spots ?? 0) . '/' . $trip->total_spots,
                'url' => route('admin.trips.edit', $trip->id),
            ];
        }

        // Detect overlaps
        $overlapGroups = [];
        foreach ($trips as $t1) {
            foreach ($trips as $t2) {
                if ($t1->id >= $t2->id) continue;
                $s1 = \Carbon\Carbon::parse($t1->start_date);
                $e1 = \Carbon\Carbon::parse($t1->end_date);
                $s2 = \Carbon\Carbon::parse($t2->start_date);
                $e2 = \Carbon\Carbon::parse($t2->end_date);

                if ($s1 <= $e2 && $e1 >= $s2) {
                    $overlapGroups[] = [$t1->id, $t2->id];
                }
            }
        }

        $overlapIds = collect($overlapGroups)->flatten()->unique()->values();

        return response()->json([
            'events' => $events,
            'overlap_ids' => $overlapIds,
            'month' => (int)$month,
            'year' => (int)$year,
        ]);
    }
}
