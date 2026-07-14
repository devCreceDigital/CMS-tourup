@extends('layouts.public')

@section('meta_title', 'Viajes - ' . (optional($agency)->name ?? 'TOUR UP'))
@section('meta_description', 'Descubre todos nuestros viajes y expediciones disponibles.')
@section('og_title', 'Viajes | ' . (optional($agency)->name ?? 'TOUR UP'))

@section('content')
    <section class="page-header">
        <div class="container">
            <p class="page-header__breadcrumb"><a href="{{ url('/') }}">Inicio</a> <span class="breadcrumb__sep">/</span> Viajes</p>
            <h1 class="page-header__title">Nuestros Viajes</h1>
            <p class="page-header__subtitle">Explora las expediciones disponibles y encuentra la próxima aventura.</p>
        </div>
    </section>

    {{-- Category filter --}}
    @if($categories && $categories->count() > 0)
    <section class="section-sm" style="padding-bottom:0;">
        <div class="container">
            <div class="flex flex-wrap gap-md justify-center">
                <a href="{{ route('public.trips') }}" class="btn btn--sm @empty(request('categoria')) btn--primary @endempty btn--pill">Todos</a>
                @foreach($categories as $cat)
                <a href="{{ route('public.trips', ['categoria' => $cat->slug]) }}" class="btn btn--sm @if(request('categoria') === $cat->slug) btn--primary @endif btn--pill">{{ $cat->name }}</a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if($trips && $trips->count() > 0)
    <section class="section">
        <div class="container">
            <div class="grid grid--3">
                @foreach($trips as $trip)
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
            <div class="pagination">
                {{ $trips->links() }}
            </div>
        </div>
    </section>
    @else
    <section class="section">
        <div class="container">
            <div class="empty-state">
                <div class="empty-state__icon"><span class="material-symbols-outlined">flight_takeoff</span></div>
                <h2 class="empty-state__title">No hay viajes disponibles</h2>
                <p class="empty-state__text">No encontramos viajes que coincidan con tu búsqueda. Prueba con otra categoría o vuelve más tarde para ver nuevas expediciones.</p>
                <a href="{{ route('public.trips') }}" class="btn btn--primary btn--pill">Ver todos los viajes</a>
            </div>
        </div>
    </section>
    @endif
@endsection
