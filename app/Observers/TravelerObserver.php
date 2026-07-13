<?php

namespace App\Observers;

use App\Models\Traveler;
use App\Services\CustomerService;

class TravelerObserver
{
    protected CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function created(Traveler $traveler): void
    {
        $customer = $this->customerService->findOrCreateFromTraveler($traveler);

        if ($customer->stage === 'lead_nuevo') {
            $customer->update(['stage' => 'confirmado']);
        }
    }

    public function updated(Traveler $traveler): void
    {
        $this->customerService->findOrCreateFromTraveler($traveler);
    }
}
