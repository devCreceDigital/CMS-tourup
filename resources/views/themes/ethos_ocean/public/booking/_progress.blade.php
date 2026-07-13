@php
    $steps = [
        1 => 'Fecha', 2 => 'Plazas', 3 => 'Datos', 4 => 'Tarifa',
        5 => 'Extras', 6 => 'Resumen', 7 => 'Pago', 8 => 'Confirmación'
    ];
@endphp
<div class="booking-progress">
    @foreach($steps as $num => $label)
        <div class="booking-progress__step {{ $num < $currentStep ? 'is-done' : ($num == $currentStep ? 'is-active' : '') }}">
            <div class="booking-progress__step-number">
                @if($num < $currentStep)
                    <span class="material-symbols-outlined" style="font-size:1.4rem;">check</span>
                @else
                    {{ $num }}
                @endif
            </div>
            <span class="hide-mobile">{{ $label }}</span>
        </div>
    @endforeach
</div>
