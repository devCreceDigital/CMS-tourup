<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel') - {{ config('app.name', 'TOUR UP CMS') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        surface: '#f8f9ff',
                        'surface-container-low': '#eff4ff',
                        'surface-container': '#e5eeff',
                        'surface-container-high': '#dce9ff',
                        'surface-container-highest': '#d3e4fe',
                        'surface-container-lowest': '#ffffff',
                        'on-surface': '#0b1c30',
                        'on-surface-variant': '#434655',
                        'on-background': '#0b1c30',
                        primary: '#004ac6',
                        'primary-container': '#2563eb',
                        'on-primary': '#ffffff',
                        'on-primary-container': '#eeefff',
                        secondary: '#565e74',
                        'secondary-container': '#dae2fd',
                        'on-secondary-container': '#5c647a',
                        tertiary: '#525657',
                        outline: '#737686',
                        'outline-variant': '#c3c6d7',
                        'surface-variant': '#d3e4fe',
                        error: '#ba1a1a',
                        'error-container': '#ffdad6',
                        'inverse-surface': '#213145',
                        'inverse-on-surface': '#eaf1ff',
                    },
                    fontFamily: {
                        inter: ['Inter', 'sans-serif'],
                    },
                    borderRadius: {
                        lg: '0.5rem',
                        xl: '0.75rem',
                    },
                },
            },
        }
    </script>
    <link rel="stylesheet" href="{{ asset('css/dashboard/admin.css') }}">
    @stack('styles')
</head>
<body class="bg-surface text-on-background font-inter">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside id="sidebar" class="sidebar-gradient h-full w-64 fixed left-0 top-0 flex flex-col py-6 px-4 shadow-lg z-50 sidebar-drawer">
            {{-- Brand --}}
            <div class="flex items-center gap-3 mb-10 px-2">
                <div class="w-10 h-10 bg-primary-container rounded-lg flex items-center justify-center text-white flex-shrink-0">
                    <span class="material-symbols-outlined text-2xl">school</span>
                </div>
                <div class="min-w-0">
                    <h1 class="text-lg font-bold text-white truncate">TOUR UP</h1>
                    <p class="text-[10px] text-surface-variant/70 tracking-widest uppercase">Agency Dashboard</p>
                </div>
                <button id="sidebarClose" class="ml-auto text-white/60 hover:text-white lg:hidden">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 space-y-1 overflow-y-auto custom-scrollbar">
                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all group
                   {{ request()->routeIs('admin.dashboard') ? 'nav-link-active' : 'text-surface-variant hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span class="text-sm font-medium">Dashboard</span>
                </a>

                {{-- Viajes --}}
                <a href="{{ route('admin.trips.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all group
                   {{ request()->routeIs('admin.trips.*') && !request()->routeIs('admin.calendar.*') ? 'nav-link-active' : 'text-surface-variant hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined">explore</span>
                    <span class="text-sm font-medium">Viajes</span>
                </a>

                {{-- Calendario --}}
                <a href="{{ route('admin.calendar.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all group
                   {{ request()->routeIs('admin.calendar.*') ? 'nav-link-active' : 'text-surface-variant hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined">calendar_month</span>
                    <span class="text-sm font-medium">Calendario</span>
                </a>

                {{-- Programas --}}
                <a href="{{ route('admin.programs.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all group
                   {{ request()->routeIs('admin.programs.*') ? 'nav-link-active' : 'text-surface-variant hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined">assignment</span>
                    <span class="text-sm font-medium">Programas</span>
                </a>

                {{-- Categorías --}}
                <a href="{{ route('admin.categories.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all group
                   {{ request()->routeIs('admin.categories.*') ? 'nav-link-active' : 'text-surface-variant hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined">category</span>
                    <span class="text-sm font-medium">Categorías</span>
                </a>

                {{-- Viajeros --}}
                <a href="{{ route('admin.travelers.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all group
                   {{ request()->routeIs('admin.travelers.*') ? 'nav-link-active' : 'text-surface-variant hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined">group</span>
                    <span class="text-sm font-medium">Viajeros</span>
                </a>

                {{-- CRM (Kanban) --}}
                <a href="{{ route('admin.crm.kanban') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all group
                   {{ request()->routeIs('admin.crm.*') ? 'nav-link-active' : 'text-surface-variant hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined">dashboard_customize</span>
                    <span class="text-sm font-medium">CRM</span>
                </a>

                {{-- Blog --}}
                <a href="{{ route('admin.blog-posts.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all group
                   {{ request()->routeIs('admin.blog-posts.*') ? 'nav-link-active' : 'text-surface-variant hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined">article</span>
                    <span class="text-sm font-medium">Blog</span>
                </a>

                {{-- FAQ --}}
                <a href="{{ route('admin.faqs.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all group
                   {{ request()->routeIs('admin.faqs.*') ? 'nav-link-active' : 'text-surface-variant hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined">help</span>
                    <span class="text-sm font-medium">FAQ</span>
                </a>

                {{-- Servicios --}}
                <a href="{{ route('admin.services.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all group
                   {{ request()->routeIs('admin.services.*') ? 'nav-link-active' : 'text-surface-variant hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined">concierge</span>
                    <span class="text-sm font-medium">Servicios</span>
                </a>

                {{-- Mensajes --}}
                <a href="{{ route('admin.contacts.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all group
                   {{ request()->routeIs('admin.contacts.*') ? 'nav-link-active' : 'text-surface-variant hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined">mail</span>
                    <span class="text-sm font-medium">Mensajes</span>
                </a>

                {{-- Separador --}}
                <div class="border-t border-white/10 my-3"></div>

                {{-- Temas --}}
                <a href="{{ route('admin.themes.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all group
                   {{ request()->routeIs('admin.themes.*') ? 'nav-link-active' : 'text-surface-variant hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined">palette</span>
                    <span class="text-sm font-medium">Temas</span>
                </a>

                {{-- Imágenes --}}
                <a href="{{ route('admin.images.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all group
                   {{ request()->routeIs('admin.images.*') ? 'nav-link-active' : 'text-surface-variant hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined">photo_library</span>
                    <span class="text-sm font-medium">Imágenes</span>
                </a>

                {{-- Ajustes --}}
                <a href="{{ route('admin.settings.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all group
                   {{ request()->routeIs('admin.settings.*') && !request()->routeIs('admin.settings.help') ? 'nav-link-active' : 'text-surface-variant hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined">settings</span>
                    <span class="text-sm font-medium">Ajustes</span>
                </a>

                {{-- Ayuda --}}
                <a href="{{ route('admin.settings.help') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all group
                   {{ request()->routeIs('admin.settings.help') ? 'nav-link-active' : 'text-surface-variant hover:bg-white/10 hover:text-white' }}">
                    <span class="material-symbols-outlined">help_outline</span>
                    <span class="text-sm font-medium">Ayuda</span>
                </a>
            </nav>

            {{-- Footer --}}
            <div class="mt-auto pt-6 border-t border-white/10">
                <div class="flex items-center gap-3 px-2">
                    <div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-white text-sm font-medium truncate">{{ Auth::user()->name }}</p>
                        <p class="text-surface-variant/60 text-xs truncate">Admin</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-surface-variant hover:text-white transition-colors" title="Cerrar sesión">
                            <span class="material-symbols-outlined">logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Sidebar Overlay --}}
        <div id="sidebarOverlay" class="fixed inset-0 z-40 bg-black/50 hidden lg:hidden"></div>

        {{-- Main Content --}}
        <main class="ml-64 flex-1 flex flex-col min-h-screen">
            {{-- Top Bar --}}
            <header class="bg-surface-container-lowest border-b border-outline-variant px-8 pt-4 pb-0 flex flex-col shrink-0">
                <div class="flex items-center justify-between h-16 mb-2">
                    <div class="flex items-center gap-4">
                        <button id="sidebarToggle" class="lg:hidden p-2 text-secondary hover:bg-surface-container rounded-lg transition-colors">
                            <span class="material-symbols-outlined">menu</span>
                        </button>
                        <div class="flex flex-col">
                            <nav class="flex items-center gap-2 text-xs text-secondary mb-0.5">
                                <span class="text-secondary">Panel</span>
                                @hasSection('breadcrumbs')
                                    <span class="material-symbols-outlined text-[12px]">chevron_right</span>
                                    <span class="text-on-background font-medium">@yield('breadcrumbs')</span>
                                @endif
                            </nav>
                            <h2 class="text-2xl font-bold text-on-background">@yield('title', 'Dashboard')</h2>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="relative hidden lg:block">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-lg">search</span>
                            <form method="GET" action="{{ route('admin.search') }}">
                                <input type="text" name="q" value="{{ request('q') }}"
                                    class="pl-10 pr-4 py-2 bg-surface rounded-lg border border-outline-variant focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none w-64 text-sm"
                                    placeholder="Buscar...">
                            </form>
                        </div>
                        <button class="p-2 text-secondary hover:bg-surface-container rounded-full transition-colors relative" title="Notificaciones">
                            <span class="material-symbols-outlined">notifications</span>
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-error rounded-full"></span>
                        </button>
                        <a href="{{ url('/') }}" target="_blank" class="p-2 text-secondary hover:bg-surface-container rounded-full transition-colors" title="Ver sitio web">
                            <span class="material-symbols-outlined">open_in_new</span>
                        </a>
                    </div>
                </div>
            </header>

            {{-- Content --}}
            <div class="flex-1 px-8 py-8 overflow-y-auto custom-scrollbar bg-surface">
                <div class="max-w-7xl mx-auto space-y-6">
                    @if (session('success'))
                        <div class="alert alert-success">
                            <span class="material-symbols-outlined text-lg">check_circle</span>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-error">
                            <span class="material-symbols-outlined text-lg">error</span>
                            {{ session('error') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const toggleBtn = document.getElementById('sidebarToggle');
            const closeBtn = document.getElementById('sidebarClose');

            function openSidebar() {
                sidebar.classList.add('open');
                overlay.classList.remove('hidden');
            }

            function closeSidebar() {
                sidebar.classList.remove('open');
                overlay.classList.add('hidden');
            }

            if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (overlay) overlay.addEventListener('click', closeSidebar);
        });
    </script>

    @stack('scripts')
</body>
</html>
