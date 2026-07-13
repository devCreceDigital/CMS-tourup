@extends('layouts.public')

@section('meta_title', 'Contacto - ' . ($agency->name ?? 'TOUR UP'))
@section('meta_description', 'Ponte en contacto con nosotros para diseñar tu próxima experiencia de viaje.')
@section('og_title', 'Contacto | ' . ($agency->name ?? 'TOUR UP'))

@section('content')
    <section class="page-header">
        <div class="container">
            <p class="page-header__breadcrumb"><a href="{{ url('/') }}">Inicio</a> <span class="breadcrumb__sep">/</span> Contacto</p>
            <h1 class="page-header__title">Contáctanos</h1>
            <p class="page-header__subtitle">Estamos aquí para ayudarte a planificar tu próximo viaje.</p>
        </div>
    </section>

    <section class="section-sm">
        <div class="container">
            <div class="grid grid--sidebar-left">
                {{-- Contact Info --}}
                <div class="info-card">
                    <h2 class="info-card__title">Información de Contacto</h2>
                    <ul class="footer__contact" style="gap:1.5rem;">
                        @if(isset($agency->address) && $agency->address)
                        <li class="footer__contact-item" style="color:var(--theme-text);">
                            <span class="material-symbols-outlined text-accent">location_on</span>
                            <span>{{ $agency->address }}</span>
                        </li>
                        @endif
                        @if(isset($agency->phone) && $agency->phone)
                        <li class="footer__contact-item" style="color:var(--theme-text);">
                            <span class="material-symbols-outlined text-accent">call</span>
                            <a href="tel:{{ $agency->phone }}" class="text-primary">{{ $agency->phone }}</a>
                        </li>
                        @endif
                        @if(isset($agency->email) && $agency->email)
                        <li class="footer__contact-item" style="color:var(--theme-text);">
                            <span class="material-symbols-outlined text-accent">mail</span>
                            <a href="mailto:{{ $agency->email }}" class="text-primary">{{ $agency->email }}</a>
                        </li>
                        @endif
                    </ul>
                </div>

                {{-- Contact Form --}}
                <div class="card card--featured">
                    <h2 class="card__title">Envíanos un mensaje</h2>
                    <p class="card__text mb-md">Rellena el formulario y te responderemos lo antes posible.</p>
                    <form action="{{ route('contacto.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label" for="name">Nombre <span class="required">*</span></label>
                            <input type="text" id="name" name="name" class="form-input" placeholder="Tu nombre completo" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="email">Email <span class="required">*</span></label>
                                <input type="email" id="email" name="email" class="form-input" placeholder="tucorreo@ejemplo.com" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="phone">Teléfono</label>
                                <input type="tel" id="phone" name="phone" class="form-input" placeholder="+34 600 000 000">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="subject">Asunto <span class="required">*</span></label>
                            <input type="text" id="subject" name="subject" class="form-input" placeholder="¿Sobre qué nos consultas?" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="message">Mensaje <span class="required">*</span></label>
                            <textarea id="message" name="message" class="form-textarea" placeholder="Cuéntanos los detalles de tu consulta..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn--primary btn--block btn--lg">
                            <span class="material-symbols-outlined">send</span> Enviar Mensaje
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
