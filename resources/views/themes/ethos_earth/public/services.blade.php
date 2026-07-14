@extends('layouts.public')

@section('meta_title', 'Servicios - ' . (optional($agency)->name ?? 'TOUR UP'))
@section('meta_description', 'Descubre todos los servicios que ofrecemos para tu experiencia de viaje.')
@section('og_title', 'Servicios | ' . (optional($agency)->name ?? 'TOUR UP'))

@section('content')
    <section class="page-header">
        <div class="container">
            <p class="page-header__breadcrumb"><a href="{{ url('/') }}">Inicio</a> <span class="breadcrumb__sep">/</span> Servicios</p>
            <h1 class="page-header__title">Nuestros Servicios</h1>
            <p class="page-header__subtitle">Soluciones completas para cada tipo de viaje y grupo.</p>
        </div>
    </section>

    @if($services && $services->count() > 0)
    <section class="section">
        <div class="container">
            <div class="grid grid--3">
                @foreach($services as $service)
                <div class="service-card">
                    <div class="service-card__icon">
                        <span class="material-symbols-outlined">{{ $service->icon ?: 'star' }}</span>
                    </div>
                    <div>
                        <h3 class="service-card__title">{{ $service->name }}</h3>
                        <p class="service-card__text">{{ $service->description }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @else
    <section class="section">
        <div class="container">
            <div class="empty-state">
                <div class="empty-state__icon"><span class="material-symbols-outlined">concierge</span></div>
                <h2 class="empty-state__title">Próximamente</h2>
                <p class="empty-state__text">Estamos preparando la información de nuestros servicios. Vuelve pronto para conocer todo lo que podemos ofrecerte.</p>
                <a href="{{ url('/contacto') }}" class="btn btn--primary btn--pill">Contáctanos</a>
            </div>
        </div>
    </section>
    @endif

    {{-- CTA --}}
    <section class="section bg-primary">
        <div class="container text-center">
            <h2 class="section-header__title text-white">¿Listo para Empezar?</h2>
            <p class="text-lg text-white mb-lg" style="opacity:0.85;max-width:48rem;margin:0 auto 3rem;">Cuéntanos qué necesitas y diseñaremos la experiencia ideal para ti.</p>
            <a href="{{ url('/contacto') }}" class="btn btn--accent btn--lg btn--pill">
                <span class="material-symbols-outlined">mail</span> Solicitar Información
            </a>
        </div>
    </section>
@endsection
