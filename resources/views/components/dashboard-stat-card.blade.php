@props([
    'title', 
    'count', 
    'icon' => 'chart', 
    'color' => 'blue', 
    'route' => null, 
    'linkText' => 'مشاهده', 
    'isFormatted' => false
])

@php
    $colors = [
        'blue' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600', 'hover' => 'hover:text-blue-800'],
        'green' => ['bg' => 'bg-green-100', 'text' => 'text-green-600', 'hover' => 'hover:text-green-800'],
        'amber' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-600', 'hover' => 'hover:text-amber-800'],
        'purple' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600', 'hover' => 'hover:text-purple-800'],
        'red' => ['bg' => 'bg-red-100', 'text' => 'text-red-600', 'hover' => 'hover:text-red-800'],
    ];
    
    $iconMap = [
        'car' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />',
        'users' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />',
        'currency' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
        'chart' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />',
    ];
@endphp

<div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
    <div class="p-6">
        <div class="flex items-center gap-4">
            <div class="flex-shrink-0 {{ $colors[$color]['bg'] }} rounded-full p-4">
                <svg class="h-8 w-8 {{ $colors[$color]['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {!! $iconMap[$icon] !!}
                </svg>
            </div>
            <div class="flex-1">
                <div class="text-sm text-gray-600 font-medium">{{ $title }}</div>
                <div class="text-3xl font-bold text-gray-900 mt-1">
                    {{ $isFormatted ? $count : number_format($count) }} @if(!$isFormatted && $title != 'سرمایه‌گذاران') ریال @endif
                </div>
                {{ $slot ?? '' }}
            </div>
        </div>
    </div>
    @if($route)
        <div class="bg-gray-50 px-6 py-3 text-sm">
            <a href="{{ $route }}" class="{{ $colors[$color]['text'] }} {{ $colors[$color]['hover'] }} font-medium flex items-center gap-1">
                {{ $linkText }}
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    @else
        <div class="bg-gray-50 px-6 py-3 text-sm text-gray-500">
            <span>{{ $linkText }}</span>
        </div>
    @endif
</div>