<div class="bg-surface-container-lowest p-6 rounded-xl card-shadow border border-outline-variant flex items-center justify-between hover:border-primary transition-colors group">
    <div>
        <p class="text-secondary text-sm font-medium mb-1">{{ $label }}</p>
        <p class="text-2xl font-bold text-on-background">{{ $value }}</p>
        @if(isset($subtext))
            <p class="text-xs {{ $subtextColor ?? 'text-green-600' }} mt-1">{{ $subtext }}</p>
        @endif
        @if(isset($progress))
            <div class="mt-2 w-full bg-surface-container rounded-full h-1.5">
                <div class="h-1.5 rounded-full {{ $progressColor ?? 'bg-primary' }}" style="width: {{ $progress }}%"></div>
            </div>
        @endif
    </div>
    <div class="w-12 h-12 rounded-full flex items-center justify-center text-2xl {{ $iconBg ?? 'bg-primary/10' }} {{ $iconColor ?? 'text-primary' }} group-hover:scale-110 transition-transform">
        @if(isset($iconFa))
            <i class="fas {{ $iconFa }}"></i>
        @else
            <span class="material-symbols-outlined">{{ $icon }}</span>
        @endif
    </div>
</div>