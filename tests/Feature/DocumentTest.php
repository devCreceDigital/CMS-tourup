<?php

namespace Tests\Feature;

use Tests\TestCase;

class DocumentTest extends TestCase
{
    public function test_rejected_status_is_preserved()
    {
        $docs = collect([
            (object) ['status' => 'rejected'],
            (object) ['status' => 'complete'],
        ]);
        $hasRejected = $docs->contains(fn($d) => $d->status === 'rejected');
        $this->assertTrue($hasRejected);

        $allComplete = $docs->every(fn($d) => $d->status === 'complete');
        $this->assertFalse($allComplete);
    }

    public function test_all_complete_when_no_rejected()
    {
        $docs = collect([
            (object) ['status' => 'complete'],
            (object) ['status' => 'complete'],
        ]);
        $hasRejected = $docs->contains(fn($d) => $d->status === 'rejected');
        $allComplete = $docs->every(fn($d) => $d->status === 'complete');
        $this->assertFalse($hasRejected);
        $this->assertTrue($allComplete);
    }
}
