@extends('layouts.public')

@section('meta_title', 'Reservar ' . $trip->name . ' - Resumen')

@section('content')
@php $themeSlug = request('preview_theme') ?: session('preview_theme') ?: ($agency->active_theme ?? 'ethos_earth'); @endphp
<section class="section-sm" style="padding-top:4rem;">
    <div class="container container-sm">
        @include('themes.' . $themeSlug . '.public.booking._progress', ['currentStep' => 6])

        <div class="card card--featured mt-lg">
            <h1 class="card__title" style="font-size:2.4rem;">Resumen de tu reserva</h1>
            <p class="card__text mb-lg">Revisa todos los detalles antes de confirmar.</p>

            <div class="booking-summary mb-lg">
                <div class="booking-summary__row">
                    <span class="booking-summary__label">Viaje</span>
                    <span class="booking-summary__value">{{ $trip->name }}</span>
                </div>
                <div class="booking-summary__row">
                    <span class="booking-summary__label">Fechas</span>
                    <span class="booking-summary__value">{{ $trip->start_date?->format('d/m/Y') }} - {{ $trip->end_date?->format('d/m/Y') }}</span>
                </div>
                <div class="booking-summary__row">
                    <span class="booking-summary__label">Nº de viajeros</span>
                    <span class="booking-summary__value">{{ $data['spots'] ?? 1 }}</span>
                </div>
                @if(isset($pricingGroup) && $pricingGroup)
                <div class="booking-summary__row">
                    <span class="booking-summary__label">Tarifa</span>
                    <span class="booking-summary__value">{{ $pricingGroup->name }}</span>
                </div>
                <div class="booking-summary__row">
                    <span class="booking-summary__label">Total</span>
                    <span class="booking-summary__value">{{ number_format($pricingGroup->installments->sum('amount') * ($data['spots'] ?? 1), 2, ',', '.') }}€</span>
                </div>
                @endif
                @if(isset($data['extras']) && !empty($data['extras']))
                <div class="booking-summary__row">
                    <span class="booking-summary__label">Extras</span>
                    <span class="booking-summary__value">{{ implode(', ', array_map('ucfirst', array_keys($data['extras']))) }}</span>
                </div>
                @endif
            </div>

            @if(isset($data['travelers']))
            <h3 class="text-lg font-semibold text-primary mb-md">Viajeros</h3>
            <div class="flex flex-col gap-sm mb-lg">
                @foreach($data['travelers'] as $i => $traveler)
                <div class="flex items-center gap-sm text-sm" style="padding:1rem;background:var(--theme-surface-low);border-radius:var(--theme-rounded);">
                    <span class="badge badge--primary" style="min-width:2.4rem;justify-content:center;">{{ $i + 1 }}</span>
                    <span class="font-semibold text-primary">{{ $traveler['first_name'] ?? '' }} {{ $traveler['last_name'] ?? '' }}</span>
                    <span class="text-muted">{{ $traveler['dni'] ?? '' }}</span>
                </div>
                @endforeach
            </div>
            @endif

            <form action="{{ route('public.booking.confirm', $trip->slug) }}" method="POST">
                @csrf
                <div class="form-check mb-lg">
                    <input type="checkbox" name="accept_terms" id="accept_terms" required>
                    <label for="accept_terms">He leído y acepto la <a href="{{ url('/terminos') }}" class="text-accent">política de privacidad</a> y las <a href="{{ url('/terminos') }}" class="text-accent">condiciones del viaje</a>. <span class="required">*</span></label>
                </div>
                @error('accept_terms') <p class="form-error mb-md">{{ $message }}</p> @enderror

                <div class="flex items-center gap-md">
                    <a href="{{ route('public.booking.step5', $trip->slug) }}" class="btn btn--ghost btn--pill">
                        <span class="material-symbols-outlined">arrow_back</span> Atrás
                    </a>
                    <button type="submit" class="btn btn--primary btn--pill">
                        <span class="material-symbols-outlined">check_circle</span> Confirmar reserva
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
