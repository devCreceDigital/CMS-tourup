@extends('layouts.public')

@section('meta_title', 'Reservar ' . $trip->name . ' - ' . (optional($agency)->name ?? 'TOUR UP'))

@section('content')
@php $themeSlug = request('preview_theme') ?: session('preview_theme') ?: (optional($agency)->active_theme ?? 'ethos_earth'); @endphp
<section class="section-sm" style="padding-top:4rem;">
    <div class="container container-sm">
        @include('themes.' . $themeSlug . '.public.booking._progress', ['currentStep' => 1])

        <div class="card card--featured mt-lg">
            <h1 class="card__title" style="font-size:2.4rem;">Confirmar fecha de viaje</h1>
            <p class="card__text mb-lg">Revisa los detalles del viaje antes de continuar.</p>

            <div class="info-card" style="background:var(--theme-surface-low);margin-bottom:2rem;">
                <div class="flex items-center gap-md">
                    <span class="material-symbols-outlined text-primary" style="font-size:3rem;">calendar_month</span>
                    <div>
                        <p class="text-lg font-semibold text-primary">{{ $trip->name }}</p>
                        <p class="text-sm text-primary flex items-center gap-sm mt-sm">
                            <span class="material-symbols-outlined" style="font-size:1.6rem;">calendar_month</span>
                            {{ $trip->start_date?->format('d/m/Y') }} - {{ $trip->end_date?->format('d/m/Y') }}
                        </p>
                        <p class="text-sm text-muted mt-sm">
                            <span class="material-symbols-outlined" style="font-size:1.4rem;vertical-align:middle;">location_on</span>
                            {{ $trip->destination }}
                            @if($trip->category) · {{ $trip->category->name }} @endif
                        </p>
                    </div>
                </div>
            </div>

            <form action="{{ route('public.booking.postStep1', $trip->slug) }}" method="POST">
                @csrf
                <div class="flex items-center gap-md">
                    <a href="{{ route('public.trip-detail', $trip->slug) }}" class="btn btn--ghost btn--pill">
                        <span class="material-symbols-outlined">arrow_back</span> Volver
                    </a>
                    <button type="submit" class="btn btn--accent btn--pill">
                        Continuar <span class="material-symbols-outlined">arrow_forward</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
