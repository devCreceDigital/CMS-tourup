<?php

namespace Tests\Feature;

use App\Models\Bus;
use App\Models\BusSeat;
use App\Models\PricingGroup;
use App\Models\PricingInstallment;
use App\Models\Traveler;
use App\Models\Trip;
use App\Services\BookingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class BookingServiceTest extends TestCase
{
    use RefreshDatabase;

    private function service(): BookingService
    {
        return app(BookingService::class);
    }

    private function makeTrip(array $overrides = []): Trip
    {
        return Trip::create(array_merge([
            'reference' => (string) Str::uuid(),
            'name' => 'Test Trip',
            'slug' => 'test-trip-' . Str::random(8),
            'status' => 'on_sale',
            'total_spots' => 10,
            'occupied_spots' => 0,
        ], $overrides));
    }

    private function travelerPayload(array $overrides = []): array
    {
        return array_merge([
            'first_name' => 'Juan',
            'last_name' => 'Perez',
            'dni' => '12345678',
            'email' => 'juan@example.com',
            'phone' => '600000000',
        ], $overrides);
    }

    public function test_creates_confirmed_booking_when_spots_available(): void
    {
        $trip = $this->makeTrip(['total_spots' => 5, 'occupied_spots' => 0]);

        $booking = $this->service()->createBooking([
            'trip_id' => $trip->id,
            'travelers' => [$this->travelerPayload()],
        ]);

        $this->assertSame('confirmed', $booking->booking_status);
        $this->assertSame(1, $trip->fresh()->occupied_spots);
    }

    public function test_creates_waitlist_booking_when_trip_is_full_instead_of_failing(): void
    {
        $trip = $this->makeTrip(['total_spots' => 1, 'occupied_spots' => 1]);

        $booking = $this->service()->createBooking([
            'trip_id' => $trip->id,
            'travelers' => [$this->travelerPayload()],
        ]);

        $this->assertSame('waitlist', $booking->booking_status);
        $this->assertSame(1, $trip->fresh()->occupied_spots, 'waitlist bookings must not consume confirmed spots');
    }

    public function test_throws_when_group_does_not_fit_in_remaining_spots(): void
    {
        $trip = $this->makeTrip(['total_spots' => 5, 'occupied_spots' => 4]);

        $this->expectException(\RuntimeException::class);

        $this->service()->createBooking([
            'trip_id' => $trip->id,
            'travelers' => [
                $this->travelerPayload(['dni' => '11111111']),
                $this->travelerPayload(['dni' => '22222222']),
            ],
        ]);
    }

    public function test_updates_existing_traveler_contact_data_on_repeat_booking(): void
    {
        $tripA = $this->makeTrip();
        $tripB = $this->makeTrip();

        $this->service()->createBooking([
            'trip_id' => $tripA->id,
            'travelers' => [$this->travelerPayload(['phone' => '111111111'])],
        ]);

        $this->service()->createBooking([
            'trip_id' => $tripB->id,
            'travelers' => [$this->travelerPayload(['phone' => '222222222'])],
        ]);

        $this->assertSame(1, Traveler::where('dni', '12345678')->count());
        $this->assertSame('222222222', Traveler::where('dni', '12345678')->first()->phone);
    }

    public function test_cancel_booking_releases_seat_and_decrements_spots(): void
    {
        $trip = $this->makeTrip(['total_spots' => 5, 'occupied_spots' => 0]);
        $bus = Bus::create(['trip_id' => $trip->id, 'name' => 'Bus 1', 'total_seats' => 10, 'rows' => 5, 'columns' => 2]);
        $seat = BusSeat::create(['bus_id' => $bus->id, 'seat_number' => 1, 'row' => 1, 'column' => 1, 'is_occupied' => false]);

        $booking = $this->service()->createBooking([
            'trip_id' => $trip->id,
            'travelers' => [$this->travelerPayload()],
            'auto_assign_seat' => true,
        ]);

        $this->assertTrue($seat->fresh()->is_occupied);
        $this->assertSame(1, $trip->fresh()->occupied_spots);

        $this->service()->cancelBooking($booking->fresh());

        $this->assertFalse($seat->fresh()->is_occupied);
        $this->assertSame(0, $trip->fresh()->occupied_spots);
        $this->assertSame('cancelled', $booking->fresh()->booking_status);
    }

    public function test_cancel_waitlist_booking_does_not_decrement_occupied_spots(): void
    {
        $trip = $this->makeTrip(['total_spots' => 1, 'occupied_spots' => 1]);

        $booking = $this->service()->createBooking([
            'trip_id' => $trip->id,
            'travelers' => [$this->travelerPayload()],
        ]);

        $this->assertSame('waitlist', $booking->booking_status);

        $this->service()->cancelBooking($booking->fresh());

        $this->assertSame(1, $trip->fresh()->occupied_spots);
        $this->assertSame('cancelled', $booking->fresh()->booking_status);
    }

    public function test_auto_assign_seat_returns_first_free_seat_number(): void
    {
        $trip = $this->makeTrip();
        $bus = Bus::create(['trip_id' => $trip->id, 'name' => 'Bus 1', 'total_seats' => 3, 'rows' => 3, 'columns' => 1]);
        BusSeat::create(['bus_id' => $bus->id, 'seat_number' => 1, 'row' => 1, 'column' => 1, 'is_occupied' => true]);
        BusSeat::create(['bus_id' => $bus->id, 'seat_number' => 2, 'row' => 2, 'column' => 1, 'is_occupied' => false]);
        BusSeat::create(['bus_id' => $bus->id, 'seat_number' => 3, 'row' => 3, 'column' => 1, 'is_occupied' => false]);

        $this->assertSame(2, $this->service()->autoAssignSeat($trip, $bus));
    }

    public function test_booking_stores_pricing_snapshot_as_array(): void
    {
        $trip = $this->makeTrip();
        $group = PricingGroup::create(['trip_id' => $trip->id, 'name' => 'Standard', 'is_active' => true]);
        PricingInstallment::create([
            'pricing_group_id' => $group->id,
            'name' => 'Deposit',
            'due_date' => now()->addDays(10),
            'amount' => 100,
            'order' => 1,
        ]);

        $booking = $this->service()->createBooking([
            'trip_id' => $trip->id,
            'travelers' => [$this->travelerPayload()],
            'pricing_group_id' => $group->id,
        ]);

        $fresh = $booking->fresh();
        $this->assertIsArray($fresh->pricing_group_snapshot);
        $this->assertSame('Standard', $fresh->pricing_group_snapshot['group_name']);
        $this->assertSame(100.0, (float) $fresh->pricing_group_snapshot['total']);
    }

    public function test_accepts_traveler_sex_values_matching_database_enum(): void
    {
        $trip = $this->makeTrip();

        $booking = $this->service()->createBooking([
            'trip_id' => $trip->id,
            'travelers' => [$this->travelerPayload(['sex' => 'male'])],
        ]);

        $this->assertSame('male', $booking->traveler->sex);
    }
}
