<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Agencia de viajes especializada en expediciones, turismo responsable y experiencias educativas.')">

    <title>@yield('meta_title', 'Agencia de Viajes') | {{ optional($agency)->name ?? 'Mi Agencia' }}</title>

    <meta property="og:title" content="@yield('og_title', 'Agencia de Viajes')">
    <meta property="og:description" content="@yield('og_description', 'Agencia de viajes especializada en expediciones, turismo responsable y experiencias educativas.')">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', asset('img/og-default.jpg'))">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="canonical" href="{{ url()->current() }}">

    @php
        $theme = request('preview_theme') ?: session('preview_theme') ?: (optional($agency)->active_theme ?? 'ethos_earth');
        $fonts = [
            'ethos_earth'  => 'family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@500;600;700',
            'ethos_ocean'  => 'family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@500;600;700',
            'ethos_peak'   => 'family=Inter:wght@300;400;500;600;700;800&family=Merriweather:wght@400;700;900',
            'ethos_sunset' => 'family=Inter:wght@300;400;500;600;700;800&family=Lora:wght@500;600;700',
        ];
        $fontQuery = $fonts[$theme] ?? $fonts['ethos_earth'];
    @endphp

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?{{ $fontQuery }}&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/themes/' . $theme . '/style.css') }}">

    @stack('styles')
</head>
<body class="bg-surface">
    <a href="#main-content" class="show-mobile" style="position:absolute;left:-9999rem;">Saltar al contenido</a>

    @include('themes.' . $theme . '.partials.header')

    <main id="main-content" role="main" style="flex:1;">
        @if (session('success'))
            <div class="container" style="margin-top:2rem;">
                <div class="alert alert--success">
                    <span class="material-symbols-outlined">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="container" style="margin-top:2rem;">
                <div class="alert alert--error">
                    <span class="material-symbols-outlined">error</span>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="container" style="margin-top:2rem;">
                <div class="alert alert--error">
                    <span class="material-symbols-outlined">error</span>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p style="margin:0;">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    @include('themes.' . $theme . '.partials.footer')

    @if(isset($agency) && isset($agency->phone) && $agency->phone)
        @php $waPhone = preg_replace('/[^0-9]/', '', $agency->phone); @endphp
        <a href="https://wa.me/{{ $waPhone }}" class="whatsapp-float" target="_blank" rel="noopener" aria-label="Contactar por WhatsApp">
            <span class="material-symbols-outlined">chat</span>
        </a>
    @endif

    <script>
    (function () {
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        if (!btn || !menu) return;
        btn.addEventListener('click', function () {
            const isOpen = menu.classList.toggle('is-open');
            this.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
        const navbar = document.querySelector('.navbar');
        if (navbar) {
            const onScroll = function () {
                if (window.scrollY > 8) navbar.classList.add('navbar-scrolled');
                else navbar.classList.remove('navbar-scrolled');
            };
            window.addEventListener('scroll', onScroll, { passive: true });
            onScroll();
        }
        document.querySelectorAll('.accordion__trigger, .faq-item__question').forEach(function (trigger) {
            trigger.addEventListener('click', function () {
                const item = this.closest('.accordion__item, .faq-item');
                if (item) item.classList.toggle('is-open');
            });
        });
    })();
    </script>

    @stack('scripts')
</body>
</html>
