<?php

namespace Tests\Feature;

use Tests\TestCase;

class BookingTest extends TestCase
{
    public function test_seat_assignment_no_false_positive()
    {
        $occupiedSeats = ['1'];
        $seatCode = '1A';
        $isOccupied = in_array($seatCode, $occupiedSeats, true);
        $this->assertFalse($isOccupied, 'String 1 should not match seat code 1A with strict comparison');
    }

    public function test_seat_format_is_correct()
    {
        for ($row = 1; $row <= 4; $row++) {
            for ($col = 1; $col <= 4; $col++) {
                $seatNumber = $row . chr(64 + $col);
                $this->assertEquals(2, strlen($seatNumber));
                $this->assertMatchesRegularExpression('/^[1-9][A-D]$/', $seatNumber);
            }
        }
    }

    public function test_auto_assign_finds_first_free()
    {
        $occupiedSeats = ['1A', '1B'];
        $allSeatCodes = ['1A', '1B', '1C', '2A', '2B', '2C'];
        $free = null;
        foreach ($allSeatCodes as $code) {
            if (!in_array($code, $occupiedSeats, true)) {
                $free = $code;
                break;
            }
        }
        $this->assertEquals('1C', $free);
    }

    public function test_auto_assign_returns_null_when_full()
    {
        $occupiedSeats = ['1A', '1B'];
        $allSeatCodes = ['1A', '1B'];
        $free = null;
        foreach ($allSeatCodes as $code) {
            if (!in_array($code, $occupiedSeats, true)) {
                $free = $code;
                break;
            }
        }
        $this->assertNull($free);
    }
}
