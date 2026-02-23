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

                <form method="POST" action="{{ route('cars.update', $car) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- عنوان -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">عنوان <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $car->title) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('title') border-red-500 @enderror" 
                                   placeholder="مثال: پژو ۲۰۶ تیپ ۲" required>
                            @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- برند -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">برند <span class="text-red-500">*</span></label>
                            <input type="text" name="brand" value="{{ old('brand', $car->brand) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('brand') border-red-500 @enderror" 
                                   placeholder="مثال: پژو" required>
                            @error('brand') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- مدل -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">مدل <span class="text-red-500">*</span></label>
                            <input type="text" name="model" value="{{ old('model', $car->model) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('model') border-red-500 @enderror" 
                                   placeholder="مثال: ۲۰۶" required>
                            @error('model') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- سال ساخت -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">سال ساخت <span class="text-red-500">*</span></label>
                            <input type="number" name="year" value="{{ old('year', $car->year) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('year') border-red-500 @enderror" 
                                   placeholder="مثال: 1400" min="1300" max="1405" required>
                            @error('year') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- کارکرد -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">کارکرد (کیلومتر) <span class="text-red-500">*</span></label>
                            <input type="number" name="kilometers" value="{{ old('kilometers', $car->kilometers) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('kilometers') border-red-500 @enderror" 
                                   placeholder="مثال: 45000" min="0" required>
                            @error('kilometers') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- رنگ -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">رنگ</label>
                            <input type="text" name="color" value="{{ old('color', $car->color) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('color') border-red-500 @enderror" 
                                   placeholder="مثال: سفید">
                            @error('color') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- نوع سوخت -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">نوع سوخت <span class="text-red-500">*</span></label>
                            <select name="fuel_type" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('fuel_type') border-red-500 @enderror" required>
                                <option value="بنزین" {{ old('fuel_type', $car->fuel_type) == 'بنزین' ? 'selected' : '' }}>بنزین</option>
                                <option value="گازوئیل" {{ old('fuel_type', $car->fuel_type) == 'گازوئیل' ? 'selected' : '' }}>گازوئیل</option>
                                <option value="هیبرید" {{ old('fuel_type', $car->fuel_type) == 'هیبرید' ? 'selected' : '' }}>هیبرید</option>
                                <option value="برقی" {{ old('fuel_type', $car->fuel_type) == 'برقی' ? 'selected' : '' }}>برقی</option>
                            </select>
                            @error('fuel_type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- گیربکس -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">گیربکس <span class="text-red-500">*</span></label>
                            <select name="transmission" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('transmission') border-red-500 @enderror" required>
                                <option value="دنده‌ای" {{ old('transmission', $car->transmission) == 'دنده‌ای' ? 'selected' : '' }}>دنده‌ای</option>
                                <option value="اتوماتیک" {{ old('transmission', $car->transmission) == 'اتوماتیک' ? 'selected' : '' }}>اتوماتیک</option>
                            </select>
                            @error('transmission') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- قیمت خرید با ویرگول -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">قیمت خرید (ریال) <span class="text-red-500">*</span></label>
                            <input type="text" name="purchase_price" id="purchase_price" value="{{ old('purchase_price', number_format($car->purchase_price)) }}" 
                                   class="price-format w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('purchase_price') border-red-500 @enderror" 
                                   placeholder="مثال: 450,000,000" min="0" required>
                            @error('purchase_price') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- تاریخ خرید (شمسی) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ خرید <span class="text-red-500">*</span></label>
                            <input type="text" name="purchase_date" id="purchase_date" value="{{ old('purchase_date', $car->jalali_purchase_date ?? $car->purchase_date) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('purchase_date') border-red-500 @enderror" 
                                   placeholder="مثال: 1402/05/15" autocomplete="off" required>
                            @error('purchase_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-500 mt-1">تاریخ را به فرمت شمسی وارد کنید (مثال: 1402/05/15)</p>
                        </div>

                        {{-- نمایش تصاویر فعلی --}}
                        @if($car->images->count() > 0)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">تصاویر فعلی</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                @foreach($car->images as $image)
                                <div class="relative group border rounded-lg overflow-hidden">
                                    <img src="{{ $image->thumbnail_url }}" class="w-full h-24 object-cover">
                                    @if($image->is_primary)
                                        <span class="absolute top-1 left-1 bg-yellow-500 text-white text-xs px-2 py-1 rounded">اصلی</span>
                                    @endif
                                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                        <a href="{{ route('cars.images', $car) }}" class="text-white text-sm bg-blue-600 px-3 py-1 rounded">
                                            مدیریت
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <a href="{{ route('cars.images', $car) }}" class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                                مدیریت تصاویر
                            </a>
                        </div>
                        @else
                        <div class="md:col-span-2">
                            <a href="{{ route('cars.images', $car) }}" class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                افزودن تصویر
                            </a>
                        </div>
                        @endif

                        <!-- وضعیت -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">وضعیت <span class="text-red-500">*</span></label>
                            <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('status') border-red-500 @enderror" required>
                                <option value="available" {{ old('status', $car->status) == 'available' ? 'selected' : '' }}>موجود</option>
                                <option value="sold" {{ old('status', $car->status) == 'sold' ? 'selected' : '' }}>فروخته شده</option>
                                <option value="reserved" {{ old('status', $car->status) == 'reserved' ? 'selected' : '' }}>رزرو</option>
                            </select>
                            @error('status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- توضیحات -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات</label>
                            <textarea name="description" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('description') border-red-500 @enderror" 
                                      placeholder="توضیحات اضافی درباره خودرو...">{{ old('description', $car->description) }}</textarea>
                            @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
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
        // تقویم شمسی
        $('#purchase_date').persianDatepicker({
            format: 'YYYY/MM/DD',
            autoClose: true,
            initialValue: true,
            calendar: {
                persian: true
            }
        });
        
        // فرمت عدد با ویرگول - روش ساده
        $('.price-format').on('input', function() {
            // حذف همه کاراکترهای غیرعددی
            var value = this.value.replace(/[^\d]/g, '');
            
            // اضافه کردن ویرگول
            if (value) {
                this.value = Number(value).toLocaleString('en-US');
            }
        });
    });
</script>
@endpush