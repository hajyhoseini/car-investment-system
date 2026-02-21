<!-- @props(['title', 'icon' => 'car', 'color' => 'blue', 'route' => null, 'viewAllText' => 'مشاهده همه'])

@php
    $gradients = [
        'blue' => 'from-blue-600 to-blue-800',
        'green' => 'from-green-600 to-green-800',
        'purple' => 'from-purple-600 to-purple-800',
        'red' => 'from-red-600 to-red-800',
    ];
    
    $hoverColors = [
        'blue' => 'hover:text-blue-600',
        'green' => 'hover:text-green-600',
        'purple' => 'hover:text-purple-600',
        'red' => 'hover:text-red-600',
    ];
    
    $iconMap = [
        'car' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />',
        'users' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />',
        'sale' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />',
        'warning' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
    ];
@endphp

<div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
    <div class="bg-gradient-to-r {{ $gradients[$color] }} px-6 py-4">
        <h3 class="text-lg font-semibold text-white flex items-center gap-2">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {!! $iconMap[$icon] !!}
            </svg>
            {{ $title }}
        </h3>
    </div>
    <div class="p-6">
        <div class="space-y-4">
            {{ $slot }}
        </div>
        @if($route)
            <div class="mt-4">
                <a href="{{ $route }}" class="text-sm {{ $hoverColors[$color] }} font-medium flex items-center gap-1">
                    {{ $viewAllText }}
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        @endif
    </div>
</div> -->