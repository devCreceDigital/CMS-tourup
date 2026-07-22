<?php

namespace App\Services;

use App\Models\Trip;
use App\Models\Traveler;
use App\Models\TripBooking;
use App\Models\PricingGroup;
use App\Models\Bus;
use App\Models\BusSeat;
use App\Models\TravelerDocument;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\BookingConfirmation;
use App\Mail\NewBookingNotification;

class BookingService
{
    public function createBooking(array $data): TripBooking
    {
        return DB::transaction(function () use ($data) {
            $trip = Trip::lockForUpdate()->findOrFail($data['trip_id']);
            $spotsRequested = count($data['travelers']);
            $reference = $data['reference'] ?? (string) Str::uuid();

            $available = $this->checkAvailability($trip, $spotsRequested);
            if (!$available['available'] && !$available['waitlist']) {
                throw new \RuntimeException($available['message']);
            }

            $pricingGroup = isset($data['pricing_group_id'])
                ? PricingGroup::with('installments')->findOrFail($data['pricing_group_id'])
                : null;

            $booking = null;

            foreach ($data['travelers'] as $i => $travelerData) {
                $traveler = Traveler::updateOrCreate(
                    ['dni' => $travelerData['dni']],
                    [
                        'first_name' => $travelerData['first_name'],
                        'last_name' => $travelerData['last_name'] ?? '',
                        'email' => $travelerData['email'] ?? null,
                        'phone' => $travelerData['phone'] ?? null,
                        'birth_date' => $travelerData['birth_date'] ?? null,
                        'sex' => $travelerData['sex'] ?? null,
                        'address' => $travelerData['address'] ?? null,
                        'notes' => $travelerData['notes'] ?? null,
                    ]
                );

                $seatData = null;
                if (!empty($data['auto_assign_seat']) && $trip->buses->isNotEmpty()) {
                    $bus = $trip->buses->first();
                    $seatNumber = $this->autoAssignSeat($trip, $bus);
                    if ($seatNumber) {
                        $seat = BusSeat::where('bus_id', $bus->id)
                            ->where('seat_number', $seatNumber)
                            ->first();
                        if ($seat) {
                            $seat->update(['is_occupied' => true]);
                            $seatData = ['bus_id' => $bus->id, 'bus_seat_id' => $seat->id];
                        }
                    }
                }

                $bookingData = [
                    'trip_id' => $trip->id,
                    'traveler_id' => $traveler->id,
                    'reference' => $reference,
                    'spots' => 1,
                    'booking_status' => $available['waitlist'] ? 'waitlist' : 'confirmed',
                    'payment_status' => 'pending',
                    'document_status' => 'pending',
                    'amount_paid' => 0,
                    'notes' => $travelerData['notes'] ?? null,
                ];

                if ($seatData) {
                    $bookingData['bus_id'] = $seatData['bus_id'];
                    $bookingData['bus_seat_id'] = $seatData['bus_seat_id'];
                }

                if ($pricingGroup) {
                    $bookingData['pricing_group_snapshot'] = [
                        'group_id' => $pricingGroup->id,
                        'group_name' => $pricingGroup->name,
                        'installments' => $pricingGroup->installments->map(function ($inst) {
                            return [
                                'name' => $inst->name,
                                'due_date' => $inst->due_date->format('Y-m-d'),
                                'amount' => $inst->amount,
                                'order' => $inst->order,
                            ];
                        })->toArray(),
                        'total' => $pricingGroup->installments->sum('amount'),
                    ];
                }

                if (isset($data['extras'])) {
                    $bookingData['extras_snapshot'] = $data['extras'];
                }

                $created = TripBooking::create($bookingData);
                if ($i === 0) {
                    $booking = $created;
                }
            }

            if (!$available['waitlist']) {
                $trip->increment('occupied_spots', $spotsRequested);
            }

            if ($booking && $pricingGroup) {
                $this->snapshotPricingAtBooking($booking, $pricingGroup);
            }

            $bookings = TripBooking::where('reference', $reference)->get();
            foreach ($bookings as $b) {
                $this->generatePendingDocuments($b);
            }

            return $booking ?? TripBooking::where('reference', $reference)->first();
        });
    }

    public function checkAvailability(Trip $trip, int $spots, ?string $date = null): array
    {
        $availableSpots = $trip->total_spots - $trip->occupied_spots;

        return [
            'available' => $availableSpots >= $spots,
            'available_spots' => $availableSpots,
            'waitlist' => $availableSpots <= 0,
            'message' => $availableSpots >= $spots
                ? 'Plazas disponibles'
                : ($availableSpots > 0
                    ? "Solo quedan {$availableSpots} plazas"
                    : 'No hay plazas disponibles. Puedes apuntarte a la lista de espera.'),
        ];
    }

    public function addToWaitlist(Trip $trip, array $travelerData): TripBooking
    {
        return DB::transaction(function () use ($trip, $travelerData) {
            $traveler = Traveler::updateOrCreate(
                ['dni' => $travelerData['dni']],
                [
                    'first_name' => $travelerData['first_name'],
                    'last_name' => $travelerData['last_name'] ?? '',
                    'email' => $travelerData['email'] ?? null,
                    'phone' => $travelerData['phone'] ?? null,
                ]
            );

            return TripBooking::create([
                'trip_id' => $trip->id,
                'traveler_id' => $traveler->id,
                'reference' => (string) Str::uuid(),
                'spots' => 1,
                'booking_status' => 'waitlist',
                'payment_status' => 'pending',
                'document_status' => 'pending',
                'amount_paid' => 0,
            ]);
        });
    }

    public function autoAssignSeat(Trip $trip, ?Bus $bus = null): ?int
    {
        if (!$bus) {
            $bus = $trip->buses()->first();
        }
        if (!$bus) return null;

        $occupiedSeats = $bus->seats()->lockForUpdate()->where('is_occupied', true)->pluck('seat_number')->toArray();

        for ($i = 1; $i <= $bus->total_seats; $i++) {
            if (!in_array($i, $occupiedSeats)) {
                return $i;
            }
        }

        return null;
    }

    public function generatePendingDocuments(TripBooking $booking): void
    {
        $existingTypes = TravelerDocument::where('trip_id', $booking->trip_id)
            ->where('traveler_id', $booking->traveler_id)
            ->pluck('type')
            ->toArray();

        $requiredDocs = [
            ['type' => 'contract', 'label' => 'Contrato de viaje'],
            ['type' => 'consent', 'label' => 'Consentimiento informado'],
            ['type' => 'itinerary_pdf', 'label' => 'Itinerario detallado'],
        ];

        foreach ($requiredDocs as $doc) {
            if (!in_array($doc['type'], $existingTypes)) {
                TravelerDocument::create([
                    'trip_id' => $booking->trip_id,
                    'traveler_id' => $booking->traveler_id,
                    'type' => $doc['type'],
                    'label' => $doc['label'],
                    'status' => 'pending',
                ]);
            }
        }
    }

    public function sendConfirmationEmails(TripBooking $booking): void
    {
        $traveler = $booking->traveler;
        if ($traveler && $traveler->email) {
            Mail::to($traveler->email)->send(new BookingConfirmation($booking));
        }

        $agencyEmail = config('mail.from.address');
        if ($agencyEmail) {
            Mail::to($agencyEmail)->send(new NewBookingNotification($booking));
        }
    }

    public function snapshotPricingAtBooking(TripBooking $booking, PricingGroup $group): void
    {
        $snapshot = $group->installments->map(function ($inst) {
            return [
                'installment_id' => $inst->id,
                'name' => $inst->name,
                'due_date' => $inst->due_date->format('Y-m-d'),
                'amount' => $inst->amount,
                'order' => $inst->order,
            ];
        });

        $booking->update([
            'pricing_group_snapshot' => [
                'group_id' => $group->id,
                'group_name' => $group->name,
                'total' => $group->installments->sum('amount'),
                'installments' => $snapshot,
            ],
        ]);
    }

    public function cancelBooking(TripBooking $booking): void
    {
        DB::transaction(function () use ($booking) {
            $trip = Trip::lockForUpdate()->findOrFail($booking->trip_id);
            $wasWaitlist = $booking->booking_status === 'waitlist';

            $booking->update(['booking_status' => 'cancelled']);
            $booking->payments()->where('status', 'pending')->update(['status' => 'cancelled']);

            if ($booking->bus_seat_id) {
                BusSeat::where('id', $booking->bus_seat_id)->update(['is_occupied' => false]);
            }

            if (!$wasWaitlist) {
                $trip->decrement('occupied_spots', $booking->spots ?? 1);
            }
        });
    }
}
