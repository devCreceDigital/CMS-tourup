@extends('layouts.public')

@section('meta_title', $post->title . ' - ' . ($agency->name ?? 'TOUR UP'))
@section('meta_description', Illuminate\Support\Str::limit(strip_tags($post->content), 160))
@section('og_title', $post->title)
@section('og_type', 'article')
@if($post->featured_image)
@section('og_image', asset('storage/' . $post->featured_image))
@endif

@section('content')
    <article>
        <section class="page-header" style="padding-bottom:2rem;">
            <div class="container container-sm">
                <p class="page-header__breadcrumb">
                    <a href="{{ url('/') }}">Inicio</a> <span class="breadcrumb__sep">/</span>
                    <a href="{{ route('blog.index') }}">Blog</a> <span class="breadcrumb__sep">/</span>
                    @if($post->category)<a href="{{ route('blog.category', $post->category->slug) }}">{{ $post->category->name }}</a> @endif
                </p>
                <h1 class="page-header__title">{{ $post->title }}</h1>
                <p class="page-header__subtitle">{{ $post->published_at?->format('d \d\e F \d\e Y') }}</p>
            </div>
        </section>

        @if($post->featured_image)
        <div class="container container-sm" style="margin-bottom:3rem;">
            <div class="img-frame img-frame--hero" style="height:32rem;">
                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}">
            </div>
        </div>
        @endif

        <section class="section-sm" style="padding-top:0;">
            <div class="container container-sm">
                <div class="editorial">
                    {!! $post->content !!}
                </div>
            </div>
        </section>

        {{-- Recent posts --}}
        @if($recent && $recent->count() > 0)
        <section class="section bg-surface-low">
            <div class="container">
                <div class="section-header">
                    <span class="section-header__tag">Sigue leyendo</span>
                    <h2 class="section-header__title">Artículos Recientes</h2>
                </div>
                <div class="grid grid--3">
                    @foreach($recent as $r)
                    <a href="{{ route('blog.show', $r->slug) }}" class="blog-card">
                        <div class="blog-card__image">
                            @if($r->featured_image)
                                <img src="{{ asset('storage/' . $r->featured_image) }}" alt="{{ $r->title }}">
                            @else
                                <div class="trip-card__image-placeholder"><span class="material-symbols-outlined">newspaper</span></div>
                            @endif
                        </div>
                        <div class="blog-card__body">
                            <div class="blog-card__meta">
                                <span>{{ $r->published_at?->format('d M, Y') }}</span>
                            </div>
                            <h3 class="blog-card__title">{{ $r->title }}</h3>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </section>
        @endif
    </article>
@endsection
