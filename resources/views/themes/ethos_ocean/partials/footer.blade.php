@php
    $social = [
        'facebook'  => \App\Models\Setting::get('facebook', ''),
        'instagram' => \App\Models\Setting::get('instagram', ''),
        'twitter'   => \App\Models\Setting::get('twitter', ''),
        'youtube'   => \App\Models\Setting::get('youtube', ''),
        'linkedin'  => \App\Models\Setting::get('linkedin', ''),
        'whatsapp'  => \App\Models\Setting::get('whatsapp', ''),
    ];
    $socialIcons = [
        'facebook'  => 'facebook',
        'instagram' => 'photo_camera',
        'twitter'   => 'tag',
        'youtube'   => 'smart_display',
        'linkedin'  => 'work',
        'whatsapp'  => 'chat',
    ];
    $footerLinks = [
        ['url' => url('/'),                'label' => 'Inicio'],
        ['url' => route('public.trips'),   'label' => 'Viajes'],
        ['url' => url('/sobre-nosotros'),  'label' => 'Sobre Nosotros'],
        ['url' => url('/servicios'),       'label' => 'Servicios'],
        ['url' => url('/preguntas-frecuentes'), 'label' => 'FAQ'],
        ['url' => url('/blog'),            'label' => 'Blog'],
        ['url' => url('/contacto'),        'label' => 'Contacto'],
        ['url' => url('/privacidad'),      'label' => 'Política de Privacidad'],
        ['url' => url('/terminos'),        'label' => 'Términos y Condiciones'],
    ];
@endphp
<footer class="footer">
    <div class="container">
        <div class="footer__grid">
            <div>
                <h3 class="footer__brand">{{ optional($agency)->name ?? 'Mi Agencia' }}</h3>
                <p class="footer__desc">{{ $agency->welcome_phrase ?? 'Diseñamos viajes responsables a medida. Creamos experiencias transformadoras con compromiso social y ambiental.' }}</p>
            </div>
            <div>
                <h4 class="footer__heading">Enlaces</h4>
                <ul class="footer__links">
                    @foreach($footerLinks as $link)
                        <li><a href="{{ $link['url'] }}">{{ $link['label'] }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 class="footer__heading">Contacto</h4>
                <ul class="footer__contact">
                    @if(isset($agency->address) && $agency->address)
                    <li class="footer__contact-item">
                        <span class="material-symbols-outlined">location_on</span>
                        <span>{{ $agency->address }}</span>
                    </li>
                    @endif
                    @if(isset(optional($agency)->phone) && optional($agency)->phone)
                    <li class="footer__contact-item">
                        <span class="material-symbols-outlined">call</span>
                        <a href="tel:{{ optional($agency)->phone }}">{{ optional($agency)->phone }}</a>
                    </li>
                    @endif
                    @if(isset($agency->email) && $agency->email)
                    <li class="footer__contact-item">
                        <span class="material-symbols-outlined">mail</span>
                        <a href="mailto:{{ $agency->email }}">{{ $agency->email }}</a>
                    </li>
                    @endif
                    @if(isset($agency->ruc) && $agency->ruc)
                    <li class="footer__contact-item">
                        <span class="material-symbols-outlined">badge</span>
                        <span>RUC: {{ $agency->ruc }}</span>
                    </li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="footer__bottom">
            <p>&copy; {{ date('Y') }} {{ optional($agency)->name ?? 'Mi Agencia' }}. Todos los derechos reservados.</p>
            <div class="footer__social">
                @foreach($social as $net => $url)
                    @if($url)
                        <a href="{{ $url }}" target="_blank" rel="noopener" aria-label="{{ ucfirst($net) }}">
                            <span class="material-symbols-outlined">{{ $socialIcons[$net] }}</span>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</footer>
