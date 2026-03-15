{{-- resources/views/car-sales/edit.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">ویرایش اطلاعات فروش</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('car-sales.show', $carSale) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            مشاهده جزئیات
                        </a>
                        <a href="{{ route('car-sales.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            بازگشت به لیست
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- اطلاعات خودرو -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-semibold mb-3">اطلاعات خودرو</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <span class="text-sm text-gray-600">خودرو:</span>
                            <div class="font-bold">{{ $carSale->car->title }}</div>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">قیمت خرید:</span>
                            <div class="font-bold text-blue-600">{{ fa_currency($carSale->car->purchase_price) }}</div>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">وضعیت فعلی:</span>
                            <div>
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                    فروخته شده
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('car-sales.update', $carSale) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- قیمت فروش -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">قیمت فروش (ریال) <span class="text-red-500">*</span></label>
                            <input type="number" name="selling_price" id="selling_price" 
                                   value="{{ old('selling_price', $carSale->selling_price) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('selling_price') border-red-500 @enderror" 
                                   min="{{ $carSale->car->purchase_price }}" required>
                            @error('selling_price') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- سود کل (محاسبه خودکار) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">سود کل</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="text" id="total_profit" name="total_profit" 
                                       value="{{ old('total_profit', fa_currency($carSale->total_profit)) }}" 
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50" readonly>
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">ریال</span>
                                </div>
                            </div>
                        </div>

                        <!-- تاریخ فروش -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ فروش <span class="text-red-500">*</span></label>
                            <input type="text" name="sale_date" id="sale_date" 
                                   value="{{ old('sale_date', jalali_date($carSale->sale_date)) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('sale_date') border-red-500 @enderror"
                                   placeholder="مثال: 1402/12/25" autocomplete="off" required>
                            @error('sale_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- نام خریدار -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">نام خریدار <span class="text-red-500">*</span></label>
                            <input type="text" name="buyer_name" value="{{ old('buyer_name', $carSale->buyer_name) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('buyer_name') border-red-500 @enderror" 
                                   required>
                            @error('buyer_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- تلفن خریدار -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تلفن خریدار <span class="text-red-500">*</span></label>
                            <input type="text" name="buyer_phone" value="{{ old('buyer_phone', $carSale->buyer_phone) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('buyer_phone') border-red-500 @enderror" 
                                   required>
                            @error('buyer_phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- هشدار تغییر اطلاعات -->
                    <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-start gap-3">
                            <svg class="h-5 w-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div>
                                <p class="text-sm text-yellow-800 font-medium">تغییر اطلاعات فروش</p>
                                <p class="text-xs text-yellow-700 mt-1">
                                    با تغییر قیمت فروش، سود کل و گزارش سود سرمایه‌گذاران به‌طور خودکار به‌روزرسانی خواهد شد.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 space-x-2 rtl:space-x-reverse">
                        <a href="{{ route('car-sales.index') }}" class="px-6 py-3 bg-gray-500 hover:bg-gray-700 text-white font-bold rounded-xl transition">
                            انصراف
                        </a>
                        <button type="submit" class="px-6 py-3 bg-purple-500 hover:bg-purple-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition transform hover:scale-105">
                            به‌روزرسانی فروش
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/persian-date@1.1.0/dist/persian-date.min.js"></script>
<script src="https://unpkg.com/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">

<script>
    $(document).ready(function() {
        // تقویم شمسی
        $('#sale_date').persianDatepicker({
            format: 'YYYY/MM/DD',
            autoClose: true,
            initialValue: true,
            calendar: {
                persian: true
            }
        });
    });

    // محاسبه خودکار سود
    document.getElementById('selling_price').addEventListener('input', function() {
        const purchasePrice = {{ $carSale->car->purchase_price }};
        const sellingPrice = parseFloat(this.value) || 0;
        const totalProfit = sellingPrice - purchasePrice;
        
        document.getElementById('total_profit').value = new Intl.NumberFormat('fa-IR').format(totalProfit) + ' ریال';
    });
</script>
@endpush