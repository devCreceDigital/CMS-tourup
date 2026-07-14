@extends('layouts.public')

@section('meta_title', 'Agencia de Viajes - ' . (optional($agency)->name ?? 'TOUR UP'))
@section('meta_description', 'Diseñamos viajes responsables a medida para escuelas, aventureros, grupos culturales y empresas.')
@section('og_title', (optional($agency)->name ?? 'TOUR UP') . ' - Viajes Responsables a Medida')
@section('og_description', 'Diseñamos experiencias de viaje únicas con compromiso social y ambiental.')

@section('content')
    {{-- Hero --}}
    <section class="hero">
        <div class="container">
            <div class="hero__content fade-in">
                <span class="hero__tag">Viajes Responsables</span>
                <h1 class="hero__title">Diseñamos viajes <span class="text-accent">responsables</span> a medida</h1>
                <p class="hero__subtitle">{{ $agency->welcome_phrase ?? 'Somos una agencia de viajes con propósito. Creamos experiencias transformadoras para escuelas, aventureros, grupos culturales y empresas.' }}</p>
                <div class="hero__actions">
                    <a href="{{ url('/contacto') }}" class="btn btn--primary btn--lg btn--pill">
                        <span class="material-symbols-outlined">send</span> Solicitar Propuesta
                    </a>
                    <a href="{{ route('public.trips') }}" class="btn btn--outline btn--lg btn--pill">
                        <span class="material-symbols-outlined">explore</span> Explorar Destinos
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Trust Bar --}}
    <section class="trust-bar">
        <div class="container">
            <div class="trust-bar__inner">
                <div class="trust-bar__item"><span class="material-symbols-outlined">verified</span><span>Certificación Sostenible</span></div>
                <div class="trust-bar__item"><span class="material-symbols-outlined">security</span><span>Seguro de Viaje Incluido</span></div>
                <div class="trust-bar__item"><span class="material-symbols-outlined">volunteer_activism</span><span>Compromiso Social</span></div>
                <div class="trust-bar__item"><span class="material-symbols-outlined">eco</span><span>Carbono Neutro</span></div>
                <div class="trust-bar__item"><span class="material-symbols-outlined">star</span><span>+10 Años de Experiencia</span></div>
            </div>
        </div>
    </section>

    {{-- Featured Trips --}}
    @if($trips && $trips->count() > 0)
    <section class="section">
        <div class="container">
            <div class="section-header">
                <span class="section-header__tag">Nuestros Viajes</span>
                <h2 class="section-header__title">Próximas Expediciones</h2>
                <p class="section-header__subtitle">Experiencias diseñadas para cada tipo de viajero, con el respaldo de años de expertise.</p>
            </div>
            <div class="grid grid--3">
                @foreach($trips->take(6) as $trip)
                <a href="{{ route('public.trip-detail', $trip->slug) }}" class="trip-card card--hover">
                    <div class="trip-card__image">
                        @if($trip->image)
                            <img src="{{ asset('storage/' . $trip->image) }}" alt="{{ $trip->name }}">
                        @else
                            <div class="trip-card__image-placeholder"><span class="material-symbols-outlined">flight_takeoff</span></div>
                        @endif
                    </div>
                    <div class="trip-card__body">
                        <div class="trip-card__meta">
                            @if($trip->category)<span class="trip-card__category">{{ $trip->category->name }}</span>@endif
                            <span class="trip-card__date">{{ $trip->start_date?->format('d M, Y') }}</span>
                        </div>
                        <h3 class="trip-card__title">{{ $trip->name }}</h3>
                        @if($trip->destination)
                        <p class="trip-card__destination"><span class="material-symbols-outlined" style="font-size:1.6rem;">location_on</span>{{ $trip->destination }}</p>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>
            @if($trips->count() > 6)
            <div class="text-center mt-lg">
                <a href="{{ route('public.trips') }}" class="btn btn--secondary btn--lg btn--pill">
                    Ver todos los viajes <span class="material-symbols-outlined" style="font-size:2rem;">arrow_forward</span>
                </a>
            </div>
            @endif
        </div>
    </section>
    @endif

    {{-- Categories --}}
    @if($categories && $categories->count() > 0)
    <section class="section bg-surface-low">
        <div class="container">
            <div class="section-header">
                <span class="section-header__tag">Categorías</span>
                <h2 class="section-header__title">Nuestros Programas</h2>
                <p class="section-header__subtitle">Experiencias diseñadas para cada tipo de viajero.</p>
            </div>
            <div class="grid grid--4">
                @foreach($categories as $category)
                <a href="{{ route('public.trips', ['categoria' => $category->slug]) }}" class="category-card card--hover">
                    <div class="category-card__icon-wrap">
                        <div class="category-card__icon">
                            <span class="material-symbols-outlined">{{ $category->icon ?: 'explore' }}</span>
                        </div>
                    </div>
                    <div class="category-card__body">
                        <h3 class="category-card__title">{{ $category->name }}</h3>
                        <p class="category-card__text">{{ $category->description ?? 'Descubre nuestros programas de viaje.' }}</p>
                        <span class="category-card__link">Ver programas <span class="material-symbols-outlined" style="font-size:1.8rem;">arrow_forward</span></span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- How We Work --}}
    <section class="section">
        <div class="container">
            <div class="section-header">
                <span class="section-header__tag">Proceso</span>
                <h2 class="section-header__title">¿Cómo Trabajamos?</h2>
                <p class="section-header__subtitle">Un proceso simple y transparente para que tu experiencia sea inolvidable.</p>
            </div>
            <div class="grid grid--4">
                <div class="process-step">
                    <div class="process-step__icon"><span class="material-symbols-outlined">lightbulb</span></div>
                    <h3 class="process-step__title">Idea</h3>
                    <p class="process-step__text">Cuéntanos tus sueños y necesidades. Diseñamos juntos el viaje perfecto para tu grupo.</p>
                </div>
                <div class="process-step">
                    <div class="process-step__icon"><span class="material-symbols-outlined">design_services</span></div>
                    <h3 class="process-step__title">Diseño</h3>
                    <p class="process-step__text">Planificamos cada detalle: itinerario, alojamiento, actividades y logística.</p>
                </div>
                <div class="process-step">
                    <div class="process-step__icon"><span class="material-symbols-outlined">assignment_turned_in</span></div>
                    <h3 class="process-step__title">Reserva</h3>
                    <p class="process-step__text">Confirmamos fechas, gestionamos pagos y te entregamos toda la documentación.</p>
                </div>
                <div class="process-step">
                    <div class="process-step__icon"><span class="material-symbols-outlined">headset_mic</span></div>
                    <h3 class="process-step__title">Soporte 24/7</h3>
                    <p class="process-step__text">Te acompañamos antes, durante y después del viaje. Siempre hay alguien disponible.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="section bg-primary">
        <div class="container text-center">
            <h2 class="section-header__title text-white">¿Listo para tu Próxima Aventura?</h2>
            <p class="text-lg text-white mb-lg" style="opacity:0.85;max-width:48rem;margin:0 auto 3rem;">Déjanos ayudarte a crear un viaje que marque la diferencia. Solicita una propuesta sin compromiso.</p>
            <div class="hero__actions" style="justify-content:center;">
                <a href="{{ url('/contacto') }}" class="btn btn--accent btn--lg btn--pill">
                    <span class="material-symbols-outlined">send</span> Solicitar Propuesta
                </a>
                <a href="tel:{{ optional($agency)->phone ?? '#' }}" class="btn btn--outline btn--lg btn--pill" style="color:var(--ee-white);border-color:rgba(255,255,255,0.5);">
                    <span class="material-symbols-outlined">call</span> Llamar Ahora
                </a>
            </div>
        </div>
    </section>
@endsection
