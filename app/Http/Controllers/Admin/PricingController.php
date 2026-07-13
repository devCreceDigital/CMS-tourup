<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PricingGroup;
use App\Models\PricingInstallment;
use App\Models\Trip;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function index(Trip $trip)
    {
        $groups = $trip->pricingGroups()->with('installments')->get();
        return view('admin.pricing.index', compact('trip', 'groups'));
    }

    public function storeGroup(Request $request, Trip $trip)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $group = $trip->pricingGroups()->create([
            'name' => $data['name'],
            'is_active' => true,
        ]);

        return redirect()->route('admin.trips.pricing.index', $trip)
            ->with('success', 'Grupo de tarifa creado correctamente.');
    }

    public function updateGroup(Request $request, PricingGroup $group)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $group->update($data);

        return redirect()->route('admin.trips.pricing.index', $group->trip_id)
            ->with('success', 'Grupo de tarifa actualizado.');
    }

    public function destroyGroup(PricingGroup $group)
    {
        $tripId = $group->trip_id;
        $group->delete();

        return redirect()->route('admin.trips.pricing.index', $tripId)
            ->with('success', 'Grupo de tarifa eliminado.');
    }

    public function storeInstallment(Request $request, PricingGroup $group)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'due_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'order' => 'nullable|integer|min:0',
        ]);

        $group->installments()->create($data);

        return redirect()->route('admin.trips.pricing.index', $group->trip_id)
            ->with('success', 'Plazo de pago añadido.');
    }

    public function updateInstallment(Request $request, PricingInstallment $installment)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'due_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'order' => 'nullable|integer|min:0',
        ]);

        $installment->update($data);

        return redirect()->route('admin.trips.pricing.index', $installment->pricingGroup->trip_id)
            ->with('success', 'Plazo de pago actualizado.');
    }

    public function destroyInstallment(PricingInstallment $installment)
    {
        $tripId = $installment->pricingGroup->trip_id;
        $installment->delete();

        return redirect()->route('admin.trips.pricing.index', $tripId)
            ->with('success', 'Plazo de pago eliminado.');
    }
}
