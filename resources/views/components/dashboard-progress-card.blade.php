@props(['title', 'icon' => 'car', 'color' => 'blue'])

@php
    $colors = [
        'blue' => 'text-blue-600',
        'amber' => 'text-amber-600',
        'purple' => 'text-purple-600',
    ];
    
    $iconMap = [
        'car' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />',
        'currency' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
    ];
@endphp

<div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
    <h3 class="text-lg font-semibold mb-5 flex items-center gap-2">
        <div class="w-8 h-8 rounded-full bg-{{ $color }}-100 flex items-center justify-center">
            <svg class="h-5 w-5 {{ $colors[$color] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {!! $iconMap[$icon] !!}
            </svg>
        </div>
        <span>{{ $title }}</span>
    </h3>

    <div class="space-y-4">
        {{ $slot }}
    </div>
</div>