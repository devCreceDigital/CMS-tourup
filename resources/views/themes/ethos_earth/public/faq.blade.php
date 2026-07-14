@extends('layouts.public')

@section('meta_title', 'Preguntas Frecuentes - ' . (optional($agency)->name ?? 'TOUR UP'))
@section('meta_description', 'Resuelve tus dudas sobre nuestros viajes, reservas y políticas.')
@section('og_title', 'Preguntas Frecuentes | ' . (optional($agency)->name ?? 'TOUR UP'))

@section('content')
    <section class="page-header">
        <div class="container">
            <p class="page-header__breadcrumb"><a href="{{ url('/') }}">Inicio</a> <span class="breadcrumb__sep">/</span> FAQ</p>
            <h1 class="page-header__title">Preguntas Frecuentes</h1>
            <p class="page-header__subtitle">Resolvemos las dudas más habituales sobre nuestros viajes.</p>
        </div>
    </section>

    @if($faqs && $faqs->count() > 0)
    <section class="section">
        <div class="container container-sm">
            <div class="accordion">
                @foreach($faqs as $faq)
                <div class="faq-item">
                    <button type="button" class="faq-item__question">
                        <span>{{ $faq->question }}</span>
                        <span class="material-symbols-outlined faq-item__icon">add</span>
                    </button>
                    <div class="faq-item__answer">
                        <div class="faq-item__answer-inner">{!! $faq->answer !!}</div>
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
                <div class="empty-state__icon"><span class="material-symbols-outlined">help</span></div>
                <h2 class="empty-state__title">Sin preguntas todavía</h2>
                <p class="empty-state__text">Aún no hemos publicado preguntas frecuentes. Si tienes alguna duda, no dudes en contactarnos directamente.</p>
                <a href="{{ url('/contacto') }}" class="btn btn--primary btn--pill">Contáctanos</a>
            </div>
        </div>
    </section>
    @endif

    {{-- Contact CTA --}}
    <section class="section bg-surface-low">
        <div class="container text-center">
            <h2 class="section-header__title">¿No encuentras tu respuesta?</h2>
            <p class="section-header__subtitle">Estamos aquí para ayudarte con cualquier consulta.</p>
            <a href="{{ url('/contacto') }}" class="btn btn--primary btn--lg btn--pill mt-md">
                <span class="material-symbols-outlined">mail</span> Contáctanos
            </a>
        </div>
    </section>
@endsection
