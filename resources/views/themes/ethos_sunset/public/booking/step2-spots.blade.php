@extends('layouts.public')

@section('meta_title', 'Reservar ' . $trip->name . ' - Plazas')

@section('content')
@php $themeSlug = request('preview_theme') ?: session('preview_theme') ?: ($agency->active_theme ?? 'ethos_earth'); @endphp
<section class="section-sm" style="padding-top:4rem;">
    <div class="container container-sm">
        @include('themes.' . $themeSlug . '.public.booking._progress', ['currentStep' => 2])

        <div class="card card--featured mt-lg">
            <h1 class="card__title" style="font-size:2.4rem;">Número de plazas</h1>
            <p class="card__text mb-lg">Selecciona cuántos viajeros quieres inscribir.</p>

            <div class="info-card" style="background:var(--theme-surface-low);margin-bottom:2rem;">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-md">
                        <span class="material-symbols-outlined text-accent" style="font-size:2.4rem;">group</span>
                        <span class="text-base font-semibold text-primary">Plazas disponibles</span>
                    </div>
                    <span class="text-2xl font-bold text-primary">{{ $availableSpots }}</span>
                </div>
            </div>

            <form action="{{ route('public.booking.postStep2', $trip->slug) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="spots">Número de viajeros <span class="required">*</span></label>
                    <input type="number" id="spots" name="spots" class="form-input" min="1" max="{{ max(1, $availableSpots) }}" value="1" required>
                    <p class="form-hint">Máximo {{ $availableSpots }} plazas disponibles para este viaje.</p>
                    @error('spots') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-md">
                    <a href="{{ route('public.booking.step1', $trip->slug) }}" class="btn btn--ghost btn--pill">
                        <span class="material-symbols-outlined">arrow_back</span> Atrás
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
