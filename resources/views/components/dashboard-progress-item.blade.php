@props(['label', 'value', 'total', 'color' => 'blue', 'isRial' => false])

@php
    $percentage = $total > 0 ? ($value / $total) * 100 : 0;
    $colorClasses = [
        'green' => 'bg-green-500',
        'red' => 'bg-red-500',
        'amber' => 'bg-amber-500',
        'blue' => 'bg-blue-500',
        'emerald' => 'bg-emerald-500',
        'yellow' => 'bg-yellow-500',
    ];
@endphp

<div>
    <div class="flex justify-between text-sm mb-1">
        <span>{{ $label }}</span>
        <span class="font-medium">{{ $isRial ? number_format($value) . ' ریال' : $value . ' خودرو' }}</span>
    </div>
    <div class="w-full bg-gray-200 rounded-full h-2.5">
        <div class="{{ $colorClasses[$color] }} h-2.5 rounded-full transition-all" style="width: {{ $percentage }}%"></div>
    </div>
</div>