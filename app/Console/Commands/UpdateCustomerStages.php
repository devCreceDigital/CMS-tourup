<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Trip;
use App\Models\TripBooking;
use Illuminate\Console\Command;

class UpdateCustomerStages extends Command
{
    protected $signature = 'crm:update-stages';
    protected $description = 'Actualiza etapas de clientes según fechas de viaje';

    public function handle()
    {
        $today = now()->startOfDay();

        $tripsStarted = Trip::whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>', $today)
            ->pluck('id');

        if ($tripsStarted->isNotEmpty()) {
            $travelerIds = TripBooking::whereIn('trip_id', $tripsStarted)
                ->where('booking_status', 'confirmed')
                ->pluck('traveler_id');

            if ($travelerIds->isNotEmpty()) {
                $updated = Customer::whereHas('travelers', function ($q) use ($travelerIds) {
                    $q->whereIn('traveler_id', $travelerIds);
                })->where('stage', 'confirmado')->update(['stage' => 'en_viaje']);

                $this->info("Clientes movidos a 'En viaje': {$updated}");
            }
        }

        $tripsEnded = Trip::whereDate('end_date', '<', $today)->pluck('id');

        if ($tripsEnded->isNotEmpty()) {
            $travelerIds = TripBooking::whereIn('trip_id', $tripsEnded)
                ->where('booking_status', 'confirmed')
                ->pluck('traveler_id');

            if ($travelerIds->isNotEmpty()) {
                $updated = Customer::whereHas('travelers', function ($q) use ($travelerIds) {
                    $q->whereIn('traveler_id', $travelerIds);
                })->where('stage', 'en_viaje')->update(['stage' => 'postventa']);

                $this->info("Clientes movidos a 'Postventa': {$updated}");
            }
        }

        $this->info('Actualización de etapas completada.');
    }
}
