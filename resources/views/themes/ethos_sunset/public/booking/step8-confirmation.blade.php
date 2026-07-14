@extends('layouts.public')

@section('meta_title', 'Reserva confirmada - ' . (optional($agency)->name ?? 'TOUR UP'))

@section('content')
@php $themeSlug = request('preview_theme') ?: session('preview_theme') ?: (optional($agency)->active_theme ?? 'ethos_earth'); @endphp
<section class="section-sm" style="padding-top:4rem;">
    <div class="container container-sm">
        @include('themes.' . $themeSlug . '.public.booking._progress', ['currentStep' => 8])

        <div class="card card--featured mt-lg text-center">
            <div class="feature-card__icon feature-card__icon--outline mx-auto mb-lg" style="width:8rem;height:8rem;font-size:4rem;">
                <span class="material-symbols-outlined">check_circle</span>
            </div>
            <h1 class="card__title" style="font-size:2.8rem;">¡Reserva confirmada!</h1>
            <p class="card__text mb-lg">Tu reserva se ha registrado correctamente. Te hemos enviado un email con los detalles.</p>

            <div class="booking-summary mb-lg" style="text-align:left;">
                <div class="booking-summary__row">
                    <span class="booking-summary__label">Número de referencia</span>
                    <span class="booking-summary__value" style="font-family:monospace;">{{ $confirmed['reference'] ?? '—' }}</span>
                </div>
                <div class="booking-summary__row">
                    <span class="booking-summary__label">Viaje</span>
                    <span class="booking-summary__value">{{ $confirmed['trip_name'] ?? $trip->name }}</span>
                </div>
            </div>

            <div class="alert alert--info" style="text-align:left;margin-bottom:2rem;">
                <span class="material-symbols-outlined">info</span>
                <div>
                    <p class="font-semibold mb-sm">Próximos pasos:</p>
                    <p>Recibirás instrucciones por email para subir la documentación necesaria y realizar el primer pago.</p>
                </div>
            </div>

            <div class="flex items-center justify-center gap-md">
                <a href="{{ route('public.trip-detail', $trip->slug) }}" class="btn btn--ghost btn--pill">
                    <span class="material-symbols-outlined">arrow_back</span> Volver al viaje
                </a>
                <a href="{{ url('/') }}" class="btn btn--primary btn--pill">
                    <span class="material-symbols-outlined">home</span> Ir al inicio
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

