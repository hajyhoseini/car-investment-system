@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">ویرایش خودرو: {{ $car->title }}</h2>
                    <a href="{{ route('cars.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
                        بازگشت به لیست
                    </a>
                </div>

                <form method="POST" action="{{ route('cars.update', $car) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- عنوان -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">عنوان</label>
                            <input type="text" name="title" value="{{ old('title', $car->title) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                            @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- برند -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">برند</label>
                            <input type="text" name="brand" value="{{ old('brand', $car->brand) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                            @error('brand') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- مدل -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">مدل</label>
                            <input type="text" name="model" value="{{ old('model', $car->model) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                            @error('model') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- سال ساخت -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">سال ساخت</label>
                            <input type="number" name="year" value="{{ old('year', $car->year) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                            @error('year') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- کارکرد -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">کارکرد (کیلومتر)</label>
                            <input type="number" name="kilometers" value="{{ old('kilometers', $car->kilometers) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                            @error('kilometers') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- رنگ -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">رنگ</label>
                            <input type="text" name="color" value="{{ old('color', $car->color) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            @error('color') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- نوع سوخت -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">نوع سوخت</label>
                            <select name="fuel_type" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                                <option value="بنزین" {{ old('fuel_type', $car->fuel_type) == 'بنزین' ? 'selected' : '' }}>بنزین</option>
                                <option value="گازوئیل" {{ old('fuel_type', $car->fuel_type) == 'گازوئیل' ? 'selected' : '' }}>گازوئیل</option>
                                <option value="هیبرید" {{ old('fuel_type', $car->fuel_type) == 'هیبرید' ? 'selected' : '' }}>هیبرید</option>
                                <option value="برقی" {{ old('fuel_type', $car->fuel_type) == 'برقی' ? 'selected' : '' }}>برقی</option>
                            </select>
                            @error('fuel_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- گیربکس -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">گیربکس</label>
                            <select name="transmission" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                                <option value="دنده‌ای" {{ old('transmission', $car->transmission) == 'دنده‌ای' ? 'selected' : '' }}>دنده‌ای</option>
                                <option value="اتوماتیک" {{ old('transmission', $car->transmission) == 'اتوماتیک' ? 'selected' : '' }}>اتوماتیک</option>
                            </select>
                            @error('transmission') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- قیمت خرید -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">قیمت خرید (ریال)</label>
                            <input type="number" name="purchase_price" value="{{ old('purchase_price', $car->purchase_price) }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                            @error('purchase_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- تاریخ خرید -->
                                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ خرید <span class="text-red-500">*</span></label>
                            <input type="text" name="purchase_date" id="purchase_date" value="{{ old('purchase_date', $todayJalali ?? '') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('purchase_date') border-red-500 @enderror" 
                                   placeholder="مثال: 1402/05/15" autocomplete="off" required>
                            @error('purchase_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-500 mt-1">تاریخ را به فرمت شمسی وارد کنید (مثال: 1402/05/15)</p>
                        </div>

                        <!-- وضعیت -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">وضعیت</label>
                            <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                                <option value="available" {{ old('status', $car->status) == 'available' ? 'selected' : '' }}>موجود</option>
                                <option value="sold" {{ old('status', $car->status) == 'sold' ? 'selected' : '' }}>فروخته شده</option>
                                <option value="reserved" {{ old('status', $car->status) == 'reserved' ? 'selected' : '' }}>رزرو</option>
                            </select>
                            @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- توضیحات -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات</label>
                            <textarea name="description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">{{ old('description', $car->description) }}</textarea>
                            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 space-x-2 rtl:space-x-reverse">
                        <a href="{{ route('cars.index') }}" class="px-6 py-3 bg-gray-500 hover:bg-gray-700 text-white font-bold rounded-xl transition">
                            انصراف
                        </a>
                        <button type="submit" class="px-6 py-3 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition transform hover:scale-105">
                            به‌روزرسانی خودرو
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
