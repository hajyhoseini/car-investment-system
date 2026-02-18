@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">مدیریت دارایی‌ها</h2>
                    <a href="{{ route('assets.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition">
                        <svg class="h-5 w-5 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        افزودن دارایی
                    </a>
                </div>

                <!-- خلاصه دارایی‌ها -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <div class="bg-blue-500 rounded-full p-2 ml-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18M3 12h18M3 18h18"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600">موجودی بانک‌ها</div>
                                <div class="text-2xl font-bold text-blue-600">{{ number_format($assets->where('type', 'bank')->sum('amount')) }} ریال</div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <div class="bg-yellow-500 rounded-full p-2 ml-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600">کل دارایی‌ها</div>
                                <div class="text-2xl font-bold text-yellow-600">{{ number_format($totalValue) }} ریال</div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <div class="bg-purple-500 rounded-full p-2 ml-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600">تعداد دارایی‌ها</div>
                                <div class="text-2xl font-bold text-purple-600">{{ $assets->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- دارایی‌های بانکی -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-3 flex items-center">
                        <svg class="h-5 w-5 ml-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                        </svg>
                        حساب‌های بانکی
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($assets->where('type', 'bank') as $asset)
                        <div class="border rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-bold text-lg">{{ $asset->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $asset->description }}</p>
                                </div>
                                <div class="text-left">
                                    <div class="text-xl font-bold text-blue-600">{{ number_format($asset->amount) }} ریال</div>
                                </div>
                            </div>
                            <div class="flex justify-end mt-2 space-x-2 rtl:space-x-reverse">
                                <a href="{{ route('assets.edit', $asset) }}" class="text-blue-600 hover:text-blue-800">
                                    <svg class="h-5 w-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- دلار و طلا -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- دلار -->
                    <div>
                        <h3 class="text-lg font-semibold mb-3 flex items-center">
                            <svg class="h-5 w-5 ml-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            دلار
                        </h3>
                        @foreach($assets->where('type', 'dollar') as $asset)
                        <div class="border rounded-lg p-4 mb-3 hover:shadow-md transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-bold">{{ $asset->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $asset->description }}</p>
                                </div>
                                <div class="text-left">
                                    <div class="text-lg font-bold text-green-600">{{ number_format($asset->amount) }} $</div>
                                    <div class="text-sm text-gray-500">≈ {{ number_format($asset->value) }} ریال</div>
                                </div>
                            </div>
                            <div class="flex justify-end mt-2">
                                <a href="{{ route('assets.edit', $asset) }}" class="text-blue-600 hover:text-blue-800">
                                    <svg class="h-5 w-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- طلا -->
                    <div>
                        <h3 class="text-lg font-semibold mb-3 flex items-center">
                            <svg class="h-5 w-5 ml-1 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            طلا
                        </h3>
                        @foreach($assets->where('type', 'gold') as $asset)
                        <div class="border rounded-lg p-4 mb-3 hover:shadow-md transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-bold">{{ $asset->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $asset->description }}</p>
                                </div>
                                <div class="text-left">
                                    <div class="text-lg font-bold text-yellow-600">{{ number_format($asset->amount) }} گرم</div>
                                    <div class="text-sm text-gray-500">≈ {{ number_format($asset->value) }} ریال</div>
                                </div>
                            </div>
                            <div class="flex justify-end mt-2">
                                <a href="{{ route('assets.edit', $asset) }}" class="text-blue-600 hover:text-blue-800">
                                    <svg class="h-5 w-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection