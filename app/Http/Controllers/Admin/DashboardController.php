<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\Traveler;
use App\Models\TripBooking;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['year' => 'nullable|integer|min:2000|max:2099']);

        $activeTrips = Trip::where('status', 'active')->count();
        $upcomingTrips = Trip::where('start_date', '>=', now())->count();
        $totalTravelers = Traveler::count();
        $totalRevenue = TripBooking::where('payment_status', 'paid')->sum('amount_paid') ?: 0;
        $pendingPayments = TripBooking::where('payment_status', 'pending')->sum('amount_paid') ?: 0;

        $query = Trip::with(['category', 'pricingGroups.installments']);

        if ($year = $request->year) {
            $query->whereYear('start_date', $year);
        }
        if ($status = $request->status) {
            $query->where('status', $status);
        }
        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%")
                  ->orWhere('destination', 'like', "%{$search}%");
            });
        }

        $trips = $query->latest()->take(10)->get();

        return view('admin.dashboard', compact('activeTrips', 'upcomingTrips', 'totalTravelers', 'totalRevenue', 'pendingPayments', 'trips'));
    }

    public function exportCsv(Request $request)
    {
        $request->validate(['year' => 'nullable|integer|min:2000|max:2099']);

        $query = Trip::with('category');

        if ($year = $request->year) {
            $query->whereYear('start_date', $year);
        }
        if ($status = $request->status) {
            $query->where('status', $status);
        }

        $trips = $query->latest()->get();

        $filename = 'viajes-' . now()->format('YmdHis') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($trips) {
            $output = fopen('php://output', 'w');
            fputs($output, "\xEF\xBB\xBF");
            fputcsv($output, ['Referencia', 'Nombre', 'Destino', 'Categoría', 'Fecha Inicio', 'Fecha Fin', 'Estado', 'Plazas Totales', 'Plazas Ocupadas', 'Creado']);

            foreach ($trips as $trip) {
                fputcsv($output, [
                    $trip->reference,
                    $trip->name,
                    $trip->destination,
                    $trip->category?->name ?? '',
                    $trip->start_date,
                    $trip->end_date,
                    $trip->status,
                    $trip->total_spots,
                    $trip->occupied_spots,
                    $trip->created_at->format('d/m/Y'),
                ]);
            }

            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }
}
