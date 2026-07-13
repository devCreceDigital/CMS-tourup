<?php

namespace App\Http\Controllers\Admin\Crm;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerInteraction;
use App\Services\CustomerService;
use Illuminate\Http\Request;

class InteractionController extends Controller
{
    protected CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function index($customerId)
    {
        $customer = Customer::findOrFail($customerId);
        $interactions = $customer->interactions()
            ->with('agent')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('panel.crm.interactions', compact('customer', 'interactions'));
    }

    public function store(Request $request, $customerId)
    {
        $request->validate([
            'channel' => 'required|in:email,whatsapp,llamada,nota',
            'type' => 'required|in:entrante,saliente',
            'content' => 'required|string|max:5000',
        ]);

        $customer = Customer::findOrFail($customerId);

        $interaction = $customer->interactions()->create([
            'channel' => $request->channel,
            'type' => $request->type,
            'content' => $request->content,
            'agent_id' => auth()->id(),
        ]);

        $this->customerService->updateLastContact($customer);

        return redirect()->back()->with('success', 'Interacción registrada correctamente.');
    }
}
