<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\CustomerInteraction;
use App\Models\Traveler;

class CustomerService
{
    public function findByTraveler(Traveler $traveler): ?Customer
    {
        $query = Customer::where(function ($q) use ($traveler) {
            if ($traveler->dni) {
                $q->where('dni', $traveler->dni);
            }
            if ($traveler->email) {
                $q->orWhere('email', $traveler->email);
            }
        });

        return $query->first();
    }

    public function findOrCreateFromTraveler(Traveler $traveler): Customer
    {
        $customer = $this->findByTraveler($traveler);

        if (!$customer) {
            $customer = Customer::create([
                'name' => trim(($traveler->first_name ?? '') . ' ' . ($traveler->last_name ?? '')),
                'email' => $traveler->email,
                'dni' => $traveler->dni,
                'phone' => $traveler->phone,
                'stage' => 'lead_nuevo',
            ]);
        }

        if (!$customer->travelers()->where('traveler_id', $traveler->id)->exists()) {
            $customer->travelers()->attach($traveler->id);
            $customer->increment('linked_travelers_count');
        }

        return $customer;
    }

    public function updateStage(Customer $customer, string $stage): Customer
    {
        $customer->update(['stage' => $stage]);
        return $customer;
    }

    public function updateLastContact(Customer $customer): Customer
    {
        $customer->update(['last_contact_at' => now()]);
        return $customer;
    }

    public function getKanbanData(): array
    {
        $data = [];
        foreach (Customer::STAGES as $key => $label) {
            $data[$key] = [
                'label' => $label,
                'customers' => Customer::where('stage', $key)
                    ->with('assignedAgent')
                    ->orderBy('last_contact_at', 'desc')
                    ->get(),
            ];
        }
        return $data;
    }

    public function getMetrics(): array
    {
        $total = Customer::count();
        $stageCounts = Customer::selectRaw('stage, count(*) as count')
            ->groupBy('stage')
            ->pluck('count', 'stage')
            ->toArray();

        $conversionRate = $total > 0
            ? (($stageCounts['confirmado'] ?? 0) / $total) * 100
            : 0;

        $recurringRate = $total > 0
            ? (Customer::where('total_trips_count', '>=', 2)->count() / $total) * 100
            : 0;

        $avgResponseHours = CustomerInteraction::where('type', 'saliente')
            ->whereHas('customer', function ($q) {
                $q->whereNotNull('created_at');
            })
            ->join('customers', 'customer_interactions.customer_id', '=', 'customers.id')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, customers.created_at, customer_interactions.created_at)) as avg_hours')
            ->value('avg_hours');

        $abandoned = Customer::where('stage', 'reserva_iniciada')
            ->where('updated_at', '<=', now()->subDays(7))
            ->count();

        $recovered = Customer::where('stage', 'confirmado')
            ->where('updated_at', '>=', now()->subDays(30))
            ->whereHas('interactions', function ($q) {
                $q->where('type', 'saliente')->where('channel', '!=', 'nota');
            })
            ->count();

        $abandonedRecoveryRate = $abandoned > 0
            ? round(($recovered / ($abandoned + $recovered)) * 100, 1)
            : 0;

        return [
            'total_customers' => $total,
            'stage_counts' => $stageCounts,
            'conversion_rate' => round($conversionRate, 1),
            'recurring_rate' => round($recurringRate, 1),
            'avg_response_time' => $avgResponseHours
                ? round($avgResponseHours, 1) . 'h'
                : '—',
            'abandoned_recovery_rate' => $abandonedRecoveryRate . '%',
        ];
    }
}
