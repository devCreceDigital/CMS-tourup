@extends('layouts.public')

@section('meta_title', 'Blog - ' . (optional($agency)->name ?? 'TOUR UP'))
@section('meta_description', 'Artículos, guías y novedades del mundo de los viajes.')
@section('og_title', 'Blog | ' . (optional($agency)->name ?? 'TOUR UP'))

@section('content')
    <section class="page-header">
        <div class="container">
            <p class="page-header__breadcrumb"><a href="{{ url('/') }}">Inicio</a> <span class="breadcrumb__sep">/</span> Blog</p>
            <h1 class="page-header__title">@isset($category) {{ $category->name }} @else Blog @endisset</h1>
            <p class="page-header__subtitle">Inspiración, guías y novedades del mundo de los viajes.</p>
        </div>
    </section>

    {{-- Category filter --}}
    @if($categories && $categories->count() > 0)
    <section class="section-sm" style="padding-bottom:0;">
        <div class="container">
            <div class="flex flex-wrap gap-md justify-center">
                <a href="{{ route('blog.index') }}" class="btn btn--sm @empty($category) btn--primary @endempty btn--pill">Todos</a>
                @foreach($categories as $cat)
                <a href="{{ route('blog.category', $cat->slug) }}" class="btn btn--sm @if(isset($category) && $category->id === $cat->id) btn--primary @endif btn--pill">{{ $cat->name }}</a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if($posts && $posts->count() > 0)
    <section class="section">
        <div class="container">
            <div class="grid grid--3">
                @foreach($posts as $post)
                <a href="{{ route('blog.show', $post->slug) }}" class="blog-card">
                    <div class="blog-card__image">
                        @if($post->featured_image)
                            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}">
                        @else
                            <div class="trip-card__image-placeholder"><span class="material-symbols-outlined">newspaper</span></div>
                        @endif
                    </div>
                    <div class="blog-card__body">
                        <div class="blog-card__meta">
                            @if($post->category)<span class="blog-card__category">{{ $post->category->name }}</span>@endif
                            <span>{{ $post->published_at?->format('d M, Y') }}</span>
                        </div>
                        <h3 class="blog-card__title">{{ $post->title }}</h3>
                        <p class="blog-card__excerpt">{{ Illuminate\Support\Str::limit(strip_tags($post->content), 120) }}</p>
                        <span class="category-card__link">Leer más <span class="material-symbols-outlined" style="font-size:1.8rem;">arrow_forward</span></span>
                    </div>
                </a>
                @endforeach
            </div>
            <div class="pagination">
                {{ $posts->links() }}
            </div>
        </div>
    </section>
    @else
    <section class="section">
        <div class="container">
            <div class="empty-state">
                <div class="empty-state__icon"><span class="material-symbols-outlined">newspaper</span></div>
                <h2 class="empty-state__title">No hay artículos todavía</h2>
                <p class="empty-state__text">Aún no hemos publicado artículos en esta categoría. Vuelve pronto para leer nuestras novedades.</p>
            </div>
        </div>
    </section>
    @endif
@endsection
