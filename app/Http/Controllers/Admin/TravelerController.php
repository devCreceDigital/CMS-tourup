<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Traveler;

class TravelerController extends Controller
{
    public function index(Request $request)
    {
        $query = Traveler::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('dni', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('course')) {
            $query->where('course', $request->course);
        }

        $travelers = $query->latest()->paginate(20);

        return view('admin.travelers.index', compact('travelers'));
    }

    public function create()
    {
        return view('admin.travelers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'dni'        => 'required|string|max:20|unique:travelers,dni',
            'birth_date' => 'nullable|date',
            'sex'        => 'nullable|string|in:male,female,other',
            'email'      => 'nullable|email|max:255|unique:travelers,email',
            'phone'      => 'nullable|string|max:20',
            'address'    => 'nullable|string|max:500',
            'notes'      => 'nullable|string',
        ]);

        Traveler::create($validated);

        return redirect()->route('admin.travelers.index')->with('success', 'Viajero creado correctamente.');
    }

    public function show($id)
    {
        $traveler = Traveler::with(['bookings.trip', 'bookings.payments', 'documents'])->findOrFail($id);

        return view('admin.travelers.show', compact('traveler'));
    }

    public function edit($id)
    {
        $traveler = Traveler::findOrFail($id);

        return view('admin.travelers.edit', compact('traveler'));
    }

    public function update(Request $request, $id)
    {
        $traveler = Traveler::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'dni'        => 'required|string|max:20|unique:travelers,dni,' . $id,
            'birth_date' => 'nullable|date',
            'sex'        => 'nullable|string|in:male,female,other',
            'email'      => 'nullable|email|max:255|unique:travelers,email,' . $id,
            'phone'      => 'nullable|string|max:20',
            'address'    => 'nullable|string|max:500',
            'notes'      => 'nullable|string',
        ]);

        $traveler->update($validated);

        return redirect()->route('admin.travelers.index')->with('success', 'Viajero actualizado correctamente.');
    }

    public function destroy($id)
    {
        $traveler = Traveler::findOrFail($id);
        $traveler->delete();

        return redirect()->route('admin.travelers.index')->with('success', 'Viajero eliminado correctamente.');
    }

    public function export()
    {
        $travelers = Traveler::latest()->get();

        $filename = 'viajeros_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($travelers) {
            $output = fopen('php://output', 'w');

            fputcsv($output, [
                'Nombres', 'Apellidos', 'DNI', 'Fecha Nacimiento',
                'Sexo', 'Email', 'Teléfono', 'Dirección', 'Notas',
            ]);

            foreach ($travelers as $traveler) {
                fputcsv($output, [
                    $traveler->first_name,
                    $traveler->last_name,
                    $traveler->dni,
                    $traveler->birth_date,
                    $traveler->sex,
                    $traveler->email,
                    $traveler->phone,
                    $traveler->address,
                    $traveler->notes,
                ]);
            }

            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }
}
