@php
    $navItems = [
        ['url' => url('/'),                'label' => 'Inicio',           'match' => '/',                  'icon' => 'home'],
        ['url' => route('public.trips'),   'label' => 'Viajes',           'match' => 'viajes*',             'icon' => 'explore'],
        ['url' => url('/sobre-nosotros'),  'label' => 'Sobre Nosotros',   'match' => 'sobre-nosotros',      'icon' => 'groups'],
        ['url' => url('/servicios'),       'label' => 'Servicios',        'match' => 'servicios',           'icon' => 'concierge'],
        ['url' => url('/preguntas-frecuentes'), 'label' => 'FAQ',         'match' => 'preguntas-frecuentes','icon' => 'help'],
        ['url' => url('/blog'),            'label' => 'Blog',             'match' => 'blog*',               'icon' => 'newspaper'],
        ['url' => url('/contacto'),        'label' => 'Contacto',         'match' => 'contacto',            'icon' => 'mail'],
    ];
    $isActive = function ($match) {
        if ($match === '/') return request()->is('/');
        return request()->is($match);
    };
@endphp
<header class="navbar" id="navbar">
    <div class="navbar__inner">
        <a href="{{ url('/') }}" class="navbar__brand" aria-label="{{ optional($agency)->name ?? 'Mi Agencia' }} — Inicio">
            @if(isset($agency) && optional($agency)->logo)
                <img src="{{ asset('storage/' . optional($agency)->logo) }}" alt="{{ optional($agency)->name ?? 'Mi Agencia' }}">
            @else
                <span>{{ optional($agency)->name ?? 'Mi Agencia' }}</span>
            @endif
        </a>

        <nav class="navbar__links" id="mobile-menu" aria-label="Navegación principal">
            @foreach($navItems as $item)
                <a href="{{ $item['url'] }}" class="{{ $isActive($item['match']) ? 'is-active' : '' }}">
                    <span class="material-symbols-outlined navbar__link-icon">{{ $item['icon'] }}</span>
                    {{ $item['label'] }}
                </a>
            @endforeach
            <a href="{{ url('/acceso-agencia') }}" class="btn btn--primary btn--sm navbar__cta">
                <span class="material-symbols-outlined" style="font-size:1.8rem;">lock</span>
                Panel Agencia
            </a>
        </nav>

        <div class="navbar__actions">
            <button id="mobile-menu-btn" class="navbar__toggle" aria-label="Abrir menú" aria-expanded="false" aria-controls="mobile-menu">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </div>
    </div>
</header>
