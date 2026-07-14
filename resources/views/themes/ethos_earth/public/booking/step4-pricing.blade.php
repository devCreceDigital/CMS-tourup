@extends('layouts.public')

@section('meta_title', 'Reservar ' . $trip->name . ' - Tarifa')

@section('content')
@php $themeSlug = request('preview_theme') ?: session('preview_theme') ?: (optional($agency)->active_theme ?? 'ethos_earth'); @endphp
<section class="section-sm" style="padding-top:4rem;">
    <div class="container container-sm">
        @include('themes.' . $themeSlug . '.public.booking._progress', ['currentStep' => 4])

        <div class="card card--featured mt-lg">
            <h1 class="card__title" style="font-size:2.4rem;">Selecciona tu tarifa</h1>
            <p class="card__text mb-lg">Elige el grupo de tarifa que te corresponde.</p>

            <form action="{{ route('public.booking.postStep4', $trip->slug) }}" method="POST">
                @csrf
                @if($trip->pricingGroups->count() > 0)
                <div class="flex flex-col gap-md mb-lg">
                    @foreach($trip->pricingGroups as $group)
                    <label class="card card--flat flex items-center gap-md" style="padding:1.5rem;cursor:pointer;">
                        <input type="radio" name="pricing_group_id" value="{{ $group->id }}" required style="width:1.8rem;height:1.8rem;accent-color:var(--theme-accent);">
                        <div class="flex-1">
                            <h3 class="text-base font-semibold text-primary">{{ $group->name }}</h3>
                            @if($group->installments->count() > 0)
                            <p class="text-sm text-muted">Total: {{ number_format($group->installments->sum('amount'), 2, ',', '.') }}€ en {{ $group->installments->count() }} plazos</p>
                            @endif
                        </div>
                    </label>
                    @endforeach
                </div>
                @else
                <div class="alert alert--info">No hay tarifas configuradas para este viaje. Contacta con la agencia.</div>
                @endif

                <div class="flex items-center gap-md">
                    <a href="{{ route('public.booking.step3', $trip->slug) }}" class="btn btn--ghost btn--pill">
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
