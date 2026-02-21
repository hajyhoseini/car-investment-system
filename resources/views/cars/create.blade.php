@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">افزودن خودرو جدید</h2>
                    <a href="{{ route('cars.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
                        بازگشت به لیست
                    </a>
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

                <form method="POST" action="{{ route('cars.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- عنوان -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">عنوان <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('title') border-red-500 @enderror" 
                                   placeholder="مثال: پژو ۲۰۶ تیپ ۲" required>
                            @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- برند -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">برند <span class="text-red-500">*</span></label>
                            <input type="text" name="brand" value="{{ old('brand') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('brand') border-red-500 @enderror" 
                                   placeholder="مثال: پژو" required>
                            @error('brand') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- مدل -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">مدل <span class="text-red-500">*</span></label>
                            <input type="text" name="model" value="{{ old('model') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('model') border-red-500 @enderror" 
                                   placeholder="مثال: ۲۰۶" required>
                            @error('model') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- سال ساخت -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">سال ساخت <span class="text-red-500">*</span></label>
                            <input type="number" name="year" value="{{ old('year') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('year') border-red-500 @enderror" 
                                   placeholder="مثال: 1400" min="1300" max="1405" required>
                            @error('year') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- کارکرد -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">کارکرد (کیلومتر) <span class="text-red-500">*</span></label>
                            <input type="number" name="kilometers" value="{{ old('kilometers') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('kilometers') border-red-500 @enderror" 
                                   placeholder="مثال: 45000" min="0" required>
                            @error('kilometers') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- رنگ -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">رنگ</label>
                            <input type="text" name="color" value="{{ old('color') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('color') border-red-500 @enderror" 
                                   placeholder="مثال: سفید">
                            @error('color') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- نوع سوخت -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">نوع سوخت <span class="text-red-500">*</span></label>
                            <select name="fuel_type" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('fuel_type') border-red-500 @enderror" required>
                                <option value="بنزین" {{ old('fuel_type') == 'بنزین' ? 'selected' : '' }}>بنزین</option>
                                <option value="گازوئیل" {{ old('fuel_type') == 'گازوئیل' ? 'selected' : '' }}>گازوئیل</option>
                                <option value="هیبرید" {{ old('fuel_type') == 'هیبرید' ? 'selected' : '' }}>هیبرید</option>
                                <option value="برقی" {{ old('fuel_type') == 'برقی' ? 'selected' : '' }}>برقی</option>
                            </select>
                            @error('fuel_type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- گیربکس -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">گیربکس <span class="text-red-500">*</span></label>
                            <select name="transmission" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('transmission') border-red-500 @enderror" required>
                                <option value="دنده‌ای" {{ old('transmission') == 'دنده‌ای' ? 'selected' : '' }}>دنده‌ای</option>
                                <option value="اتوماتیک" {{ old('transmission') == 'اتوماتیک' ? 'selected' : '' }}>اتوماتیک</option>
                            </select>
                            @error('transmission') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- قیمت خرید -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">قیمت خرید (ریال) <span class="text-red-500">*</span></label>
                            <input type="number" name="purchase_price" value="{{ old('purchase_price') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('purchase_price') border-red-500 @enderror" 
                                   placeholder="مثال: 450000000" min="0" required>
                            @error('purchase_price') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- تاریخ خرید (شمسی) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ خرید <span class="text-red-500">*</span></label>
                            <input type="text" name="purchase_date" id="purchase_date" value="{{ old('purchase_date', $todayJalali ?? '') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('purchase_date') border-red-500 @enderror" 
                                   placeholder="مثال: 1402/05/15" autocomplete="off" required>
                            @error('purchase_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-500 mt-1">تاریخ را به فرمت شمسی وارد کنید (مثال: 1402/05/15)</p>
                        </div>

                        <!-- توضیحات -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات</label>
                            <textarea name="description" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('description') border-red-500 @enderror" 
                                      placeholder="توضیحات اضافی درباره خودرو...">{{ old('description') }}</textarea>
                            @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 space-x-2 rtl:space-x-reverse">
                        <a href="{{ route('cars.index') }}" class="px-6 py-3 bg-gray-500 hover:bg-gray-700 text-white font-bold rounded-xl transition">
                            انصراف
                        </a>
                        <button type="submit" class="px-6 py-3 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition transform hover:scale-105">
                            ذخیره خودرو
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
        $('#purchase_date').persianDatepicker({
            format: 'YYYY/MM/DD',
            autoClose: true,
            initialValue: true,
            calendar: {
                persian: true
            }
        });
    });
</script>
@endpush