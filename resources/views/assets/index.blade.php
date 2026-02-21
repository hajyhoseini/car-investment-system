@extends('layouts.app')

@section('content')
<div class="py-10 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
            <div class="p-6 md:p-8">
                <!-- هدر + دکمه ایجاد -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800">مدیریت دارایی‌ها</h2>

                    @can('create assets')
                        <a href="{{ route('assets.create') }}"
                           class="inline-flex items-center gap-x-2 bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 px-5 rounded-lg shadow-md transition focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            افزودن دارایی جدید
                        </a>
                    @endcan
                </div>

                <!-- کارت‌های آماری خلاصه -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-10">
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-6 text-center">
                        <div class="flex justify-center mb-3">
                            <div class="bg-blue-600 rounded-full p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18M3 12h18M3 18h18" />
                                </svg>
                            </div>
                        </div>
                        <div class="text-sm text-blue-700 font-medium">موجودی حساب‌های بانکی</div>
                        <div class="text-3xl font-bold text-blue-800 mt-2">
                            {{ number_format($assets->where('type', 'bank')->sum('amount')) }}
                            <span class="text-xl">ریال</span>
                        </div>
                    </div>

                    <div class="bg-amber-50 border border-amber-100 rounded-xl p-6 text-center">
                        <div class="flex justify-center mb-3">
                            <div class="bg-amber-600 rounded-full p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="text-sm text-amber-700 font-medium">ارزش کل دارایی‌ها</div>
                        <div class="text-3xl font-bold text-amber-800 mt-2">
                            {{ number_format($totalValue ?? 0) }}
                            <span class="text-xl">ریال</span>
                        </div>
                    </div>

                    <div class="bg-purple-50 border border-purple-100 rounded-xl p-6 text-center">
                        <div class="flex justify-center mb-3">
                            <div class="bg-purple-600 rounded-full p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                        </div>
                        <div class="text-sm text-purple-700 font-medium">تعداد کل دارایی‌ها</div>
                        <div class="text-3xl font-bold text-purple-800 mt-2">{{ $assets->count() }}</div>
                    </div>
                </div>

                <!-- حساب‌های بانکی -->
                <div class="mb-12">
                    <h3 class="text-xl font-semibold text-gray-800 mb-5 flex items-center gap-x-2">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                        </svg>
                        حساب‌های بانکی
                    </h3>

                    @if($assets->where('type', 'bank')->isNotEmpty())
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($assets->where('type', 'bank') as $asset)
                                <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-md transition-all">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h4 class="font-bold text-lg text-gray-900">{{ $asset->name }}</h4>
                                            <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $asset->description ?? 'بدون توضیح' }}</p>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <div class="text-2xl font-bold text-blue-700">
                                                {{ number_format($asset->amount) }}
                                                <span class="text-base font-normal">ریال</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex justify-end">
                                        @can('edit assets')
                                            <a href="{{ route('assets.edit', $asset) }}"
                                               class="text-blue-600 hover:text-blue-800 transition flex items-center gap-x-1.5 text-sm font-medium">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                ویرایش
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-10 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <p>هنوز هیچ حساب بانکی ثبت نشده است.</p>
                        </div>
                    @endif
                </div>

                <!-- دلار و طلا -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- دلار -->
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-5 flex items-center gap-x-2">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            موجودی دلار
                        </h3>

                        @if($assets->where('type', 'dollar')->isNotEmpty())
                            <div class="space-y-5">
                                @foreach($assets->where('type', 'dollar') as $asset)
                                    <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-md transition-all">
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <h4 class="font-bold text-lg text-gray-900">{{ $asset->name }}</h4>
                                                <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $asset->description ?? 'بدون توضیح' }}</p>
                                            </div>
                                            <div class="text-right shrink-0">
                                                <div class="text-2xl font-bold text-green-700">
                                                    {{ number_format($asset->amount, 2) }}
                                                    <span class="text-base font-normal">دلار</span>
                                                </div>
                                                @if($asset->value)
                                                    <div class="text-sm text-gray-600 mt-1">
                                                        ≈ {{ number_format($asset->value) }} ریال
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex justify-end">
                                            @can('edit assets')
                                                <a href="{{ route('assets.edit', $asset) }}"
                                                   class="text-blue-600 hover:text-blue-800 transition flex items-center gap-x-1.5 text-sm font-medium">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    ویرایش
                                                </a>
                                            @endcan
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-10 text-center text-gray-500">
                                هنوز هیچ دارایی دلاری ثبت نشده است.
                            </div>
                        @endif
                    </div>

                    <!-- طلا -->
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-5 flex items-center gap-x-2">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            موجودی طلا
                        </h3>

                        @if($assets->where('type', 'gold')->isNotEmpty())
                            <div class="space-y-5">
                                @foreach($assets->where('type', 'gold') as $asset)
                                    <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-md transition-all">
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <h4 class="font-bold text-lg text-gray-900">{{ $asset->name }}</h4>
                                                <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $asset->description ?? 'بدون توضیح' }}</p>
                                            </div>
                                            <div class="text-right shrink-0">
                                                <div class="text-2xl font-bold text-yellow-700">
                                                    {{ number_format($asset->amount, 2) }}
                                                    <span class="text-base font-normal">گرم</span>
                                                </div>
                                                @if($asset->value)
                                                    <div class="text-sm text-gray-600 mt-1">
                                                        ≈ {{ number_format($asset->value) }} ریال
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex justify-end">
                                            @can('edit assets')
                                                <a href="{{ route('assets.edit', $asset) }}"
                                                   class="text-blue-600 hover:text-blue-800 transition flex items-center gap-x-1.5 text-sm font-medium">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    ویرایش
                                                </a>
                                            @endcan
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-10 text-center text-gray-500">
                                هنوز هیچ دارایی طلایی ثبت نشده است.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection