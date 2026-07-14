@extends('layouts.public')

@section('meta_title', $trip->name . ' ' . ($trip->start_date ? $trip->start_date->format('Y') : '') . ' - ' . (optional($agency)->name ?? 'TOUR UP'))
@section('meta_description', Illuminate\Support\Str::limit(strip_tags($trip->description), 160))
@section('og_title', $trip->name)
@section('og_type', 'article')
@if($trip->image)
@section('og_image', asset('storage/' . $trip->image))
@endif

@section('content')
@if($trip->exists)

{{-- Sticky Trip Nav --}}
<div class="trip-nav">
    <div class="trip-nav__inner">
        <div class="trip-nav__brand">
            @if(optional($agency)->logo ?? null)
                <img src="{{ asset('storage/' . optional($agency)->logo) }}" alt="{{ optional($agency)->name }}">
            @else
                <span class="trip-nav__brand-name">{{ optional($agency)->name ?? 'TOUR UP' }}</span>
            @endif
            <span class="trip-nav__trip-name">{{ $trip->name }}</span>
        </div>
        <nav class="trip-nav__anchors">
            @if($trip->itineraryDays->count() > 0)
            <a href="#itinerario"><span class="material-symbols-outlined">route</span>Itinerario</a>
            @endif
            @if($trip->pricingGroups->count() > 0)
            <a href="#pagos"><span class="material-symbols-outlined">payments</span>Pagos</a>
            @endif
            @if($trip->accommodations->count() > 0)
            <a href="#alojamiento"><span class="material-symbols-outlined">hotel</span>Alojamiento</a>
            @endif
        </nav>
        @if($bookingEnabled)
        <a href="{{ route('public.booking.step1', $trip->slug) }}" class="btn btn--accent btn--sm btn--pill">
            <span class="material-symbols-outlined" style="font-size:1.8rem;">check_circle</span> Inscribirse
        </a>
        @endif
    </div>
</div>

{{-- Hero Banner --}}
<section class="hero hero--image hero--centered" style="background-image: @if($trip->image) url('{{ asset('storage/' . $trip->image) }}') @else none @endif; background-size:cover; background-position:center; position:relative;">
    <div style="position:absolute;inset:0;background:rgba(0,0,0,0.55);"></div>
    <div class="container" style="position:relative;z-index:1;">
        <div class="hero__content">
            <div class="flex flex-wrap gap-sm justify-center mb-md">
                @if($trip->category)
                <span class="badge badge--white"><span class="material-symbols-outlined" style="font-size:1.4rem;">tag</span>{{ $trip->category->name }}</span>
                @endif
                @if($trip->destination)
                <span class="badge badge--white"><span class="material-symbols-outlined" style="font-size:1.4rem;">location_on</span>{{ $trip->destination }}</span>
                @endif
                @if($trip->start_date && $trip->end_date)
                <span class="badge badge--white"><span class="material-symbols-outlined" style="font-size:1.4rem;">calendar_month</span>{{ $trip->start_date->format('d M') }} - {{ $trip->end_date->format('d M, Y') }}</span>
                @endif
            </div>
            <h1 class="hero__title">
                {{ $trip->name }}
                @if($trip->start_date)<span class="text-accent"> {{ $trip->start_date->format('Y') }}</span>@endif
            </h1>
            <p class="hero__subtitle">{{ $phrases['hero_subtitle'] ?: ($trip->category->name ?? 'Experiencia única') }}</p>
            @if($bookingEnabled)
            <div class="hero__actions" style="justify-content:center;">
                <a href="{{ route('public.booking.step1', $trip->slug) }}" class="btn btn--accent btn--lg btn--pill">
                    <span class="material-symbols-outlined">how_to_register</span> Inscribirse ahora
                </a>
            </div>
            @endif
            <div class="trust-bar__inner mt-lg" style="gap:2rem;">
                <div class="trust-bar__item" style="color:rgba(255,255,255,0.85);"><span class="material-symbols-outlined text-accent">star</span><span>Gestión 100% Online</span></div>
                <div class="trust-bar__item" style="color:rgba(255,255,255,0.85);"><span class="material-symbols-outlined text-accent">security</span><span>Seguridad Total</span></div>
                <div class="trust-bar__item" style="color:rgba(255,255,255,0.85);"><span class="material-symbols-outlined text-accent">credit_card</span><span>Pago Fraccionado</span></div>
            </div>
        </div>
    </div>
</section>

{{-- Trip Summary Bar --}}
<section class="section-sm" style="padding:2rem 0;">
    <div class="container">
        <div class="card card--flat flex flex-wrap justify-between items-center gap-md" style="padding:2rem;">
            <div class="flex items-center gap-md">
                <span class="material-symbols-outlined text-accent" style="font-size:2.4rem;">location_on</span>
                <div>
                    <p class="text-label text-muted">Destinos</p>
                    <p class="text-lg font-semibold text-primary">{{ $trip->destination ?? 'Por confirmar' }}</p>
                </div>
            </div>
            @if($trip->start_date && $trip->end_date)
            <div class="flex items-center gap-md">
                <span class="material-symbols-outlined text-accent" style="font-size:2.4rem;">calendar_month</span>
                <div>
                    <p class="text-label text-muted">Fechas</p>
                    <p class="text-lg font-semibold text-primary">{{ $trip->start_date->format('d/m/Y') }} — {{ $trip->end_date->format('d/m/Y') }}</p>
                </div>
            </div>
            @endif
            <div class="flex items-center gap-md">
                <span class="material-symbols-outlined text-accent" style="font-size:2.4rem;">group</span>
                <div>
                    <p class="text-label text-muted">Plazas</p>
                    <p class="text-lg font-semibold text-primary">{{ $availableSpots }} disponibles</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Main grid: content + sidebar --}}
<div class="container section-sm" style="padding-top:0;">
    <div class="grid--sidebar">
        {{-- Main column --}}
        <div class="stack-lg">
            {{-- Description --}}
            @if($trip->description)
            <section>
                <div class="section-header" style="text-align:left;margin-bottom:2rem;">
                    <span class="section-header__tag">Resumen</span>
                    <h2 class="section-header__title">Sobre esta experiencia</h2>
                </div>
                <div class="editorial">{!! $trip->description !!}</div>
            </section>
            @endif

            {{-- Why travel with us --}}
            <section>
                <div class="section-header" style="text-align:left;margin-bottom:2rem;">
                    <span class="section-header__tag">Confianza</span>
                    <h2 class="section-header__title">Por qué viajar con nosotros</h2>
                </div>
                <div class="grid grid--2">
                    <div class="card card--flat feature-card--horizontal" style="padding:2rem;">
                        <div class="feature-card__icon feature-card__icon--outline" style="width:4.8rem;height:4.8rem;font-size:2rem;"><span class="material-symbols-outlined">health_and_safety</span></div>
                        <div>
                            <h3 class="text-lg font-semibold text-primary mb-sm">Viaja tranquilo</h3>
                            <p class="text-sm text-muted">Incluye seguro de asistencia médica y responsabilidad civil.</p>
                        </div>
                    </div>
                    <div class="card card--flat feature-card--horizontal" style="padding:2rem;">
                        <div class="feature-card__icon feature-card__icon--outline" style="width:4.8rem;height:4.8rem;font-size:2rem;"><span class="material-symbols-outlined">account_balance_wallet</span></div>
                        <div>
                            <h3 class="text-lg font-semibold text-primary mb-sm">Financia sin estrés</h3>
                            <p class="text-sm text-muted">Divide el pago en plazos cómodos según el plan de cada viaje.</p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Fechas a recordar (Pricing) --}}
            @if($trip->pricingGroups->count() > 0)
            <section id="pagos">
                <div class="section-header" style="text-align:left;margin-bottom:2rem;">
                    <span class="section-header__tag">Pagos</span>
                    <h2 class="section-header__title">Fechas a recordar</h2>
                </div>
                @foreach($trip->pricingGroups as $group)
                    @if($group->installments->count() > 0)
                    <h3 class="text-lg font-semibold text-primary mb-md">{{ $group->name }}</h3>
                    <div class="table-wrap mb-lg">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Concepto</th>
                                    <th>Importe</th>
                                    <th>Antes de</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($group->installments as $installment)
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-sm font-semibold"><span class="material-symbols-outlined text-accent" style="font-size:1.4rem;">payments</span>{{ $installment->name }}</div>
                                    </td>
                                    <td class="font-semibold">{{ number_format($installment->amount, 2, ',', '.') }}€</td>
                                    <td>{{ \Carbon\Carbon::parse($installment->due_date)->format('d/m/Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                @endforeach
            </section>
            @endif

            {{-- Hero image --}}
            @if($trip->image)
            <div class="img-frame img-frame--hero" style="height:32rem;">
                <img src="{{ asset('storage/' . $trip->image) }}" alt="{{ $trip->name }}">
            </div>
            @endif

            {{-- Itinerary --}}
            @if($trip->itineraryDays->count() > 0)
            <section id="itinerario">
                <div class="section-header" style="text-align:left;margin-bottom:2rem;">
                    <span class="section-header__tag">Itinerario</span>
                    <h2 class="section-header__title">Conoce el itinerario</h2>
                </div>
                <div class="accordion">
                    @foreach($trip->itineraryDays->sortBy('order') as $day)
                    <div class="accordion__item">
                        <button type="button" class="accordion__trigger">
                            <span class="flex items-center gap-sm">
                                <span class="material-symbols-outlined text-accent">today</span>
                                Día {{ $day->order }}: {{ $day->title }}
                            </span>
                            <span class="material-symbols-outlined accordion__icon">expand_more</span>
                        </button>
                        <div class="accordion__body">
                            <div class="accordion__content">
                                @if($day->date)<p class="text-label text-muted mb-md">{{ \Carbon\Carbon::parse($day->date)->format('l, d F, Y') }}</p>@endif
                                @if($day->description)<p class="mb-md">{!! $day->description !!}</p>@endif
                                @if($day->activities && $day->activities->count() > 0)
                                <div class="timeline timeline--vertical" style="margin-top:2rem;">
                                    @foreach($day->activities->sortBy('time') as $activity)
                                    <div class="timeline__item">
                                        <div class="timeline__icon"><span class="material-symbols-outlined" style="font-size:1.4rem;">{{ $activity->type === 'transport' ? 'directions_bus' : ($activity->type === 'meal' ? 'restaurant' : ($activity->type === 'accommodation' ? 'hotel' : 'explore')) }}</span></div>
                                        @if($activity->time)<p class="timeline__time">{{ $activity->time }}</p>@endif
                                        <h4 class="timeline__title">{{ $activity->title }}</h4>
                                        <p class="timeline__desc">{!! $activity->description !!}</p>
                                        @if($activity->important_notes)
                                        <div class="timeline__note"><strong>Nota importante:</strong> {!! $activity->important_notes !!}</div>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif

            {{-- Accommodations --}}
            @if($trip->accommodations->count() > 0)
            <section id="alojamiento">
                <div class="section-header" style="text-align:left;margin-bottom:2rem;">
                    <span class="section-header__tag">Alojamiento</span>
                    <h2 class="section-header__title">Tu alojamiento</h2>
                </div>
                <div class="grid grid--2">
                    @foreach($trip->accommodations as $accommodation)
                    <div class="card card--flat" style="overflow:hidden;padding:0;">
                        <div style="height:14rem;background:var(--theme-surface-high);overflow:hidden;">
                            @if($accommodation->image)
                            <img src="{{ asset('storage/' . $accommodation->image) }}" alt="{{ $accommodation->name }}" style="width:100%;height:100%;object-fit:cover;">
                            @else
                            <div class="trip-card__image-placeholder"><span class="material-symbols-outlined">hotel</span></div>
                            @endif
                        </div>
                        <div style="padding:1.5rem;">
                            <h3 class="text-lg font-semibold text-primary mb-sm">{{ $accommodation->name }}</h3>
                            <p class="text-sm text-muted flex items-center gap-sm"><span class="material-symbols-outlined" style="font-size:1.4rem;">location_on</span>{{ $accommodation->location }} / {{ $accommodation->stars }} Estrellas</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif
        </div>

        {{-- Sidebar --}}
        <aside class="sidebar">
            {{-- Inscription CTA --}}
            @if($bookingEnabled)
            <div class="info-card info-card--accent text-center">
                <h3 class="info-card__title">Inscríbete ahora</h3>
                <p class="info-card__text">{{ $availableSpots }} plazas disponibles</p>
                <a href="{{ route('public.booking.step1', $trip->slug) }}" class="btn btn--white btn--block btn--pill">
                    <span class="material-symbols-outlined">how_to_register</span> Inscribirse
                </a>
            </div>
            @endif

            {{-- Price from --}}
            @if($priceFrom > 0)
            <div class="info-card text-center">
                <p class="text-label text-muted">Precio desde</p>
                <p class="data-block__value">{{ number_format($priceFrom, 2, ',', '.') }}€</p>
                <p class="text-sm text-muted">Plan de pago en plazos disponible</p>
            </div>
            @endif

            {{-- Benefits --}}
            <div class="info-card">
                <h3 class="info-card__title" style="font-size:1.6rem;">Beneficios incluidos</h3>
                <div class="flex flex-col gap-md">
                    <div class="flex items-center gap-sm"><span class="material-symbols-outlined text-accent">security</span><span class="text-sm">Seguridad 24h</span></div>
                    <div class="flex items-center gap-sm"><span class="material-symbols-outlined text-accent">explore</span><span class="text-sm">Guía local experto</span></div>
                    <div class="flex items-center gap-sm"><span class="material-symbols-outlined text-accent">restaurant</span><span class="text-sm">Todo incluido</span></div>
                </div>
            </div>

            {{-- Useful links --}}
            <div class="info-card">
                <h3 class="info-card__title" style="font-size:1.6rem;"><span class="material-symbols-outlined text-accent">menu_book</span> Te interesa</h3>
                <div class="flex flex-col gap-sm">
                    <a href="{{ route('faq') }}" class="flex items-center justify-between text-sm" style="padding:1rem;border:1px solid var(--theme-border);border-radius:var(--theme-rounded);"><span class="font-semibold text-primary">Preguntas Frecuentes</span><span class="material-symbols-outlined text-muted">chevron_right</span></a>
                    <a href="{{ url('/contacto') }}" class="flex items-center justify-between text-sm" style="padding:1rem;border:1px solid var(--theme-border);border-radius:var(--theme-rounded);"><span class="font-semibold text-primary">Contactar con la agencia</span><span class="material-symbols-outlined text-muted">chevron_right</span></a>
                </div>
            </div>

            {{-- Info note --}}
            <div class="info-card" style="background:var(--theme-surface-high);">
                <div class="flex items-center gap-sm mb-sm">
                    <span class="material-symbols-outlined text-accent">info</span>
                    <h4 class="text-lg font-semibold text-primary">A tener en cuenta</h4>
                </div>
                <p class="text-sm text-muted">Se establece un plan de pagos por plazos. El primer pago confirma tu reserva de plaza.</p>
            </div>
        </aside>
    </div>
</div>

{{-- WhatsApp CTA --}}
<section class="section-sm bg-surface-low">
    <div class="container">
        <div class="card card--flat flex flex-wrap items-center justify-between gap-md" style="padding:2.5rem;">
            <div>
                <h3 class="text-2xl font-bold text-primary">¿Tienes dudas?</h3>
                <p class="text-muted mt-sm">Estamos aquí para ayudarte en cada paso.</p>
            </div>
            @if(optional($agency)->phone ?? null)
            @php $waPhone = preg_replace('/[^0-9]/', '', optional($agency)->phone); @endphp
            <a href="https://wa.me/{{ $waPhone }}" target="_blank" rel="noopener" class="btn btn--lg btn--pill" style="background:#25D366;color:#fff;border-color:#25D366;">
                <span class="material-symbols-outlined">chat</span> Habla con nosotros por WhatsApp
            </a>
            @endif
        </div>
    </div>
</section>

@else
{{-- Trip not found fallback --}}
<section class="section">
    <div class="container">
        <div class="empty-state">
            <div class="empty-state__icon"><span class="material-symbols-outlined">explore_off</span></div>
            <h2 class="empty-state__title">Viaje no encontrado</h2>
            <p class="empty-state__text">El viaje que buscas no está disponible o no existe.</p>
            <a href="{{ route('public.trips') }}" class="btn btn--primary btn--pill">Ver viajes disponibles</a>
        </div>
    </div>
</section>
@endif
@endsection
