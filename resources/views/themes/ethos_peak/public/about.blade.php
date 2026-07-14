@extends('layouts.public')

@section('meta_title', 'Sobre Nosotros - ' . (optional($agency)->name ?? 'TOUR UP'))
@section('meta_description', 'Conoce nuestra historia, equipo y compromiso con el turismo responsable.')
@section('og_title', 'Sobre Nosotros | ' . (optional($agency)->name ?? 'TOUR UP'))

@section('content')
    <section class="page-header">
        <div class="container">
            <p class="page-header__breadcrumb"><a href="{{ url('/') }}">Inicio</a> <span class="breadcrumb__sep">/</span> Sobre Nosotros</p>
            <h1 class="page-header__title">Sobre Nosotros</h1>
            <p class="page-header__subtitle">Conoce la historia y el equipo detrás de cada experiencia de viaje.</p>
        </div>
    </section>

    {{-- History --}}
    <section class="section-sm">
        <div class="container">
            <div class="editorial">
                <h2>Nuestra Historia</h2>
                <p>{{ $agency->about ?? 'Somos una agencia de viajes con propósito, dedicada a crear experiencias transformadoras que combinan aventura, aprendizaje y compromiso social y ambiental.' }}</p>
                <p>Desde nuestros inicios, hemos diseñado cada viaje pensando en el impacto positivo tanto para los viajeros como para las comunidades que visitamos. Creemos en un turismo responsable que respeta el entorno, valora las culturas locales y genera recuerdos imborrables.</p>
            </div>
        </div>
    </section>

    {{-- Trust badges --}}
    <section class="trust-bar">
        <div class="container">
            <div class="trust-bar__inner">
                <div class="trust-bar__item"><span class="material-symbols-outlined">verified</span><span>Certificación Sostenible</span></div>
                <div class="trust-bar__item"><span class="material-symbols-outlined">security</span><span>Seguro de Viaje Incluido</span></div>
                <div class="trust-bar__item"><span class="material-symbols-outlined">eco</span><span>Carbono Neutro</span></div>
                <div class="trust-bar__item"><span class="material-symbols-outlined">star</span><span>+10 Años de Experiencia</span></div>
            </div>
        </div>
    </section>

    {{-- Team --}}
    @if($team && count($team) > 0)
    <section class="section">
        <div class="container">
            <div class="section-header">
                <span class="section-header__tag">Equipo</span>
                <h2 class="section-header__title">Las Personas Detrás del Viaje</h2>
                <p class="section-header__subtitle">Un equipo apasionado por crear experiencias inolvidables.</p>
            </div>
            <div class="grid grid--4">
                @foreach($team as $member)
                <div class="team-card">
                    <div class="team-card__photo">
                        @if($member['photo'])
                            <img src="{{ asset('storage/' . $member['photo']) }}" alt="{{ $member['name'] }}">
                        @else
                            {{ strtoupper(substr($member['name'], 0, 1)) }}
                        @endif
                    </div>
                    <h3 class="team-card__name">{{ $member['name'] }}</h3>
                    <p class="team-card__role">{{ $member['position'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- CTA --}}
    <section class="section bg-primary">
        <div class="container text-center">
            <h2 class="section-header__title text-white">Hablemos de tu Próximo Viaje</h2>
            <p class="text-lg text-white mb-lg" style="opacity:0.85;max-width:48rem;margin:0 auto 3rem;">Estamos aquí para ayudarte a diseñar la experiencia perfecta para tu grupo.</p>
            <a href="{{ url('/contacto') }}" class="btn btn--accent btn--lg btn--pill">
                <span class="material-symbols-outlined">mail</span> Contáctanos
            </a>
        </div>
    </section>
@endsection
