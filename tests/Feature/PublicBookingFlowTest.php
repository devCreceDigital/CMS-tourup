<?php

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\Setting;
use App\Models\Trip;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PublicBookingFlowTest extends TestCase
{
    use RefreshDatabase;

    private function installAgency(): Agency
    {
        return Agency::create([
            'name' => 'Test Agency',
            'email' => 'agency@example.com',
            'phone' => '000000000',
            'active_theme' => 'ethos_earth',
            'is_installed' => true,
        ]);
    }

    private function makeTrip(): Trip
    {
        return Trip::create([
            'reference' => (string) Str::uuid(),
            'name' => 'Test Trip',
            'slug' => 'test-trip',
            'status' => 'on_sale',
            'total_spots' => 5,
            'occupied_spots' => 0,
        ]);
    }

    public function test_booking_wizard_is_blocked_when_bookings_are_disabled(): void
    {
        $this->installAgency();
        $trip = $this->makeTrip();
        Setting::set('booking_enabled', 'false');

        $response = $this->get(route('public.booking.step1', $trip->slug));

        $response->assertForbidden();
    }

    public function test_booking_wizard_is_reachable_when_bookings_are_enabled(): void
    {
        $this->installAgency();
        $trip = $this->makeTrip();
        Setting::set('booking_enabled', 'true');

        $response = $this->get(route('public.booking.step1', $trip->slug));

        $response->assertOk();
    }
}
