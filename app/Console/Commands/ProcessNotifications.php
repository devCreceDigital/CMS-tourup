<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\TripBooking;
use App\Models\PricingInstallment;
use App\Services\NotificationEngine;
use Illuminate\Console\Command;

class ProcessNotifications extends Command
{
    protected $signature = 'crm:process-notifications';
    protected $description = 'Procesa notificaciones automáticas del CRM';

    protected NotificationEngine $engine;

    public function __construct(NotificationEngine $engine)
    {
        parent::__construct();
        $this->engine = $engine;
    }

    public function handle()
    {
        $today = now()->startOfDay();

        $this->processPaymentReminders($today);
        $this->processDocumentReminders($today);
        $this->processPreTripReminders($today);
        $this->processPostTripSurveys($today);
        $this->processLeadsWithoutResponse($today);

        $this->info('Notificaciones procesadas correctamente.');
    }

    protected function processPaymentReminders($today)
    {
        $dueSoon = $today->copy()->addDays(3);
        $installments = PricingInstallment::whereDate('due_date', $dueSoon)->get();

        foreach ($installments as $inst) {
            $bookings = TripBooking::whereHas('trip.pricingGroups.installments', function ($q) use ($inst) {
                $q->where('id', $inst->id);
            })->where('booking_status', 'confirmed')->get();

            foreach ($bookings as $booking) {
                $customer = Customer::whereHas('travelers', function ($q) use ($booking) {
                    $q->where('traveler_id', $booking->traveler_id);
                })->first();

                if ($customer) {
                    $this->engine->evaluate('pago_vencido', $customer, [
                        'installment_name' => $inst->name,
                        'amount' => $inst->amount,
                        'due_date' => $inst->due_date->format('d/m/Y'),
                        'trip_name' => $booking->trip->name,
                    ]);
                }
            }
        }
    }

    protected function processDocumentReminders($today)
    {
        $deadline = $today->copy()->addDays(7);
        $bookings = TripBooking::where('document_status', 'pending')
            ->whereHas('trip', function ($q) use ($deadline) {
                $q->whereDate('start_date', '<=', $deadline);
            })->get();

        foreach ($bookings as $booking) {
            $customer = Customer::whereHas('travelers', function ($q) use ($booking) {
                $q->where('traveler_id', $booking->traveler_id);
            })->first();

            if ($customer) {
                $this->engine->evaluate('doc_faltante', $customer, [
                    'trip_name' => $booking->trip->name,
                    'start_date' => $booking->trip->start_date->format('d/m/Y'),
                ]);
            }
        }
    }

    protected function processPreTripReminders($today)
    {
        $reminderDate = $today->copy()->addDay();
        $bookings = TripBooking::where('booking_status', 'confirmed')
            ->whereHas('trip', function ($q) use ($reminderDate) {
                $q->whereDate('start_date', $reminderDate);
            })->get();

        foreach ($bookings as $booking) {
            $customer = Customer::whereHas('travelers', function ($q) use ($booking) {
                $q->where('traveler_id', $booking->traveler_id);
            })->first();

            if ($customer) {
                $this->engine->evaluate('pre_viaje', $customer, [
                    'trip_name' => $booking->trip->name,
                    'start_date' => $booking->trip->start_date->format('d/m/Y'),
                    'destination' => $booking->trip->destination,
                ]);
            }
        }
    }

    protected function processPostTripSurveys($today)
    {
        $surveyDate = $today->copy()->subDays(3);
        $bookings = TripBooking::where('booking_status', 'confirmed')
            ->whereHas('trip', function ($q) use ($surveyDate) {
                $q->whereDate('end_date', $surveyDate);
            })->get();

        foreach ($bookings as $booking) {
            $customer = Customer::whereHas('travelers', function ($q) use ($booking) {
                $q->where('traveler_id', $booking->traveler_id);
            })->first();

            if ($customer) {
                $this->engine->evaluate('post_viaje', $customer, [
                    'trip_name' => $booking->trip->name,
                ]);
            }
        }
    }

    protected function processLeadsWithoutResponse($today)
    {
        $weekAgo = $today->copy()->subDays(7);
        $leads = Customer::where('stage', 'lead_nuevo')
            ->where('last_contact_at', '<=', $weekAgo)
            ->orWhere(function ($q) use ($weekAgo) {
                $q->whereNull('last_contact_at')
                  ->where('created_at', '<=', $weekAgo);
            })->get();

        foreach ($leads as $customer) {
            $this->engine->evaluate('lead_sin_respuesta', $customer, [
                'days_since_contact' => $customer->last_contact_at
                    ? $customer->last_contact_at->diffInDays($today)
                    : $customer->created_at->diffInDays($today),
            ]);
        }
    }
}
