@extends('layouts.public')

@section('meta_title', 'Reservar ' . $trip->name . ' - Extras')

@section('content')
@php $themeSlug = request('preview_theme') ?: session('preview_theme') ?: ($agency->active_theme ?? 'ethos_earth'); @endphp
<section class="section-sm" style="padding-top:4rem;">
    <div class="container container-sm">
        @include('themes.' . $themeSlug . '.public.booking._progress', ['currentStep' => 5])

        <div class="card card--featured mt-lg">
            <h1 class="card__title" style="font-size:2.4rem;">Opciones adicionales</h1>
            <p class="card__text mb-lg">Personaliza tu experiencia con estas opciones opcionales.</p>

            <form action="{{ route('public.booking.postStep5', $trip->slug) }}" method="POST">
                @csrf
                <div class="flex flex-col gap-md mb-lg">
                    <label class="form-check card card--flat" style="padding:1.5rem;align-items:flex-start;">
                        <input type="checkbox" name="extras[room_preference]" value="individual">
                        <div>
                            <span class="text-base font-semibold text-primary">Habitación individual</span>
                            <p class="text-sm text-muted">Preferencia de habitación individual (sujeto a disponibilidad).</p>
                        </div>
                    </label>
                    <label class="form-check card card--flat" style="padding:1.5rem;align-items:flex-start;">
                        <input type="checkbox" name="extras[special_menu]" value="vegetarian">
                        <div>
                            <span class="text-base font-semibold text-primary">Menú especial</span>
                            <p class="text-sm text-muted">Vegetariano, celíaco o alergias alimentarias.</p>
                        </div>
                    </label>
                    <label class="form-check card card--flat" style="padding:1.5rem;align-items:flex-start;">
                        <input type="checkbox" name="extras[insurance]" value="yes">
                        <div>
                            <span class="text-base font-semibold text-primary">Seguro adicional</span>
                            <p class="text-sm text-muted">Cobertura extendida para el viaje.</p>
                        </div>
                    </label>
                </div>

                <div class="flex items-center gap-md">
                    <a href="{{ route('public.booking.step4', $trip->slug) }}" class="btn btn--ghost btn--pill">
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
