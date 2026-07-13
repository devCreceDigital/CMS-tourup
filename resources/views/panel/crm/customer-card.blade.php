<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 cursor-pointer hover:shadow-md transition" draggable="true" data-customer-id="{{ $customer->id }}">
    <div class="flex items-start justify-between mb-2">
        <h4 class="font-semibold text-gray-900 text-sm">{{ $customer->name }}</h4>
        <span class="text-xs text-gray-400">{{ $customer->last_contact_at?->diffForHumans() ?? '—' }}</span>
    </div>
    @if($customer->email)
    <p class="text-xs text-gray-500 truncate">{{ $customer->email }}</p>
    @endif
    @if($customer->phone)
    <p class="text-xs text-gray-500">{{ $customer->phone }}</p>
    @endif
    <div class="flex items-center justify-between mt-3 pt-2 border-t border-gray-100">
        <span class="text-xs text-gray-400">
            {{ $customer->total_trips_count }} viaje(s)
        </span>
        <a href="{{ url('/panel-agencia/crm/cliente/' . $customer->id) }}" class="text-xs text-green-600 hover:text-green-700 font-medium">
            Ver ficha
        </a>
    </div>
</div>
