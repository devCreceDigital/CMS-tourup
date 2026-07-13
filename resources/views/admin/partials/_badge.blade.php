@php
    $colors = [
        'success' => 'bg-green-100 text-green-700',
        'warning' => 'bg-yellow-100 text-yellow-700',
        'danger' => 'bg-red-100 text-red-700',
        'info' => 'bg-blue-100 text-blue-700',
        'gray' => 'bg-surface-container text-secondary',
        'purple' => 'bg-purple-100 text-purple-700',
        'primary' => 'bg-primary/10 text-primary',
    ];
    $type = $type ?? 'gray';
    $class = $colors[$type] ?? $colors['gray'];
@endphp
<span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-tighter {{ $class }}">
    @if(isset($icon))<span class="material-symbols-outlined text-[14px] mr-1">{{ $icon }}</span>@endif
    {{ $slot }}
</span>