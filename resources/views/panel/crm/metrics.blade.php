@extends('layouts.admin')

@section('title', 'Métricas CRM')
@section('page-title', 'Métricas del CRM')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-green-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 font-medium">Total Clientes</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $metrics['total_customers'] }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 font-medium">Tasa de Conversión</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $metrics['conversion_rate'] }}%</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-history text-purple-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 font-medium">Clientes Recurrentes</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $metrics['recurring_rate'] }}%</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-orange-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 font-medium">Tiempo Respuesta Promedio</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $metrics['avg_response_time'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <h3 class="font-semibold text-gray-900 mb-4">Distribución por Etapa</h3>
        <div class="space-y-3">
            @foreach(App\Models\Customer::STAGES as $key => $label)
            <div class="flex items-center">
                <span class="w-48 text-sm text-gray-600">{{ $label }}</span>
                <div class="flex-1 bg-gray-100 rounded-full h-4">
                    @php
                        $count = $metrics['stage_counts'][$key] ?? 0;
                        $total = $metrics['total_customers'] ?: 1;
                        $pct = round(($count / $total) * 100);
                    @endphp
                    <div class="bg-green-500 h-4 rounded-full" style="width: {{ $pct }}%"></div>
                </div>
                <span class="ml-3 text-sm font-semibold text-gray-700 w-16 text-right">{{ $count }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
