@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">جزئیات دارایی: {{ $asset->name }}</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('assets.edit', $asset) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            ویرایش
                        </a>
                        <a href="{{ route('assets.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            بازگشت به لیست
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- اطلاعات اصلی -->
                    <div class="bg-amber-50 p-6 rounded-xl">
                        <h3 class="text-lg font-semibold mb-4 text-amber-600">اطلاعات اصلی</h3>
                        <div class="space-y-4">
                            <div>
                                <span class="text-sm text-gray-600">نوع دارایی:</span>
                                <div class="text-lg font-medium">
                                    @if($asset->type == 'bank')
                                        حساب بانکی
                                    @elseif($asset->type == 'dollar')
                                        دلار
                                    @elseif($asset->type == 'gold')
                                        طلا
                                    @endif
                                </div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">نام:</span>
                                <div class="text-lg font-medium">{{ $asset->name }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">مقدار:</span>
                                <div class="text-lg font-medium">
                                    @if($asset->type == 'bank')
                                        {{ number_format($asset->amount) }} ریال
                                    @elseif($asset->type == 'dollar')
                                        {{ number_format($asset->amount) }} دلار
                                    @elseif($asset->type == 'gold')
                                        {{ number_format($asset->amount) }} گرم
                                    @endif
                                </div>
                            </div>
                            @if($asset->type != 'bank')
                            <div>
                                <span class="text-sm text-gray-600">ارزش به ریال:</span>
                                <div class="text-xl font-bold text-amber-600">{{ number_format($asset->value) }} ریال</div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- توضیحات -->
                    <div class="bg-gray-50 p-6 rounded-xl">
                        <h3 class="text-lg font-semibold mb-4 text-amber-600">توضیحات</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $asset->description ?? 'توضیحاتی ثبت نشده است.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection