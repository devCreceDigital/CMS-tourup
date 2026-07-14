<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Enums\TripStatus;

class TripStatusTest extends TestCase
{
    public function test_trip_status_values()
    {
        $valid = TripStatus::values();
        $this->assertContains('active', $valid);
        $this->assertContains('on_sale', $valid);
        $this->assertContains('completed', $valid);
        $this->assertNotContains('archived', $valid);
    }

    public function test_trip_status_labels()
    {
        $this->assertEquals('Activo', TripStatus::label('active'));
        $this->assertEquals('Cancelado', TripStatus::label('cancelled'));
    }
}
