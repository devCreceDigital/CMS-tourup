@extends('layouts.public')

@section('meta_title', 'Reservar ' . $trip->name . ' - Datos de los viajeros')

@section('content')
@php $themeSlug = request('preview_theme') ?: session('preview_theme') ?: (optional($agency)->active_theme ?? 'ethos_earth'); @endphp
<section class="section-sm" style="padding-top:4rem;">
    <div class="container container-sm">
        @include('themes.' . $themeSlug . '.public.booking._progress', ['currentStep' => 3])

        <div class="card card--featured mt-lg">
            <h1 class="card__title" style="font-size:2.4rem;">Datos de los viajeros</h1>
            <p class="card__text mb-lg">Completa la información de cada viajero.</p>

            <form action="{{ route('public.booking.postStep3', $trip->slug) }}" method="POST">
                @csrf
                @for($i = 0; $i < ($data['spots'] ?? 0); $i++)
                <div class="{{ $i < ($data['spots'] ?? 1) - 1 ? 'mb-lg pb-lg' : 'mb-lg' }}" style="{{ $i < ($data['spots'] ?? 1) - 1 ? 'border-bottom:1px solid var(--theme-border);' : '' }}">
                    <h3 class="text-lg font-semibold text-primary mb-md flex items-center gap-sm">
                        <span class="badge badge--primary" style="width:2.8rem;height:2.8rem;border-radius:50%;justify-content:center;">{{ $i + 1 }}</span>
                        Viajero {{ $i + 1 }}
                    </h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Nombre <span class="required">*</span></label>
                            <input type="text" name="travelers[{{ $i }}][first_name]" class="form-input" required>
                            @error("travelers.{$i}.first_name") <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Apellidos</label>
                            <input type="text" name="travelers[{{ $i }}][last_name]" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">DNI/Pasaporte <span class="required">*</span></label>
                            <input type="text" name="travelers[{{ $i }}][dni]" class="form-input" required>
                            @error("travelers.{$i}.dni") <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="travelers[{{ $i }}][email]" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Teléfono</label>
                            <input type="tel" name="travelers[{{ $i }}][phone]" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Fecha de nacimiento</label>
                            <input type="date" name="travelers[{{ $i }}][birth_date]" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Sexo</label>
                            <select name="travelers[{{ $i }}][sex]" class="form-select">
                                <option value="">Seleccionar</option>
                                <option value="M">Masculino</option>
                                <option value="F">Femenino</option>
                            </select>
                        </div>
                    </div>
                </div>
                @endfor

                <div class="flex items-center gap-md pt-md">
                    <a href="{{ route('public.booking.step2', $trip->slug) }}" class="btn btn--ghost btn--pill">
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

