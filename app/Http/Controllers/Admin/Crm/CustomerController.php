<?php

namespace App\Http\Controllers\Admin\Crm;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function index()
    {
        $kanbanData = $this->customerService->getKanbanData();
        $metrics = $this->customerService->getMetrics();
        $stages = Customer::STAGES;

        return view('panel.crm.kanban', compact('kanbanData', 'metrics', 'stages'));
    }

    public function list()
    {
        $customers = Customer::with('assignedAgent')
            ->orderBy('last_contact_at', 'desc')
            ->paginate(20);

        return view('panel.crm.list', compact('customers'));
    }

    public function show($id)
    {
        $customer = Customer::with(['interactions.agent', 'travelers', 'assignedAgent'])
            ->findOrFail($id);

        $stages = Customer::STAGES;

        return view('panel.crm.customer-show', compact('customer', 'stages'));
    }

    public function updateStage(Request $request, $id)
    {
        $request->validate(['stage' => 'required|string|in:' . implode(',', array_keys(Customer::STAGES))]);

        $customer = Customer::findOrFail($id);
        $this->customerService->updateStage($customer, $request->stage);

        return redirect()->back()->with('success', 'Etapa actualizada correctamente.');
    }

    public function updateStageAjax(Request $request, $id)
    {
        $request->validate(['stage' => 'required|string|in:' . implode(',', array_keys(Customer::STAGES))]);

        $customer = Customer::findOrFail($id);
        $this->customerService->updateStage($customer, $request->stage);

        return response()->json(['success' => true]);
    }

    public function metrics()
    {
        $metrics = $this->customerService->getMetrics();

        return view('panel.crm.metrics', compact('metrics'));
    }
}
