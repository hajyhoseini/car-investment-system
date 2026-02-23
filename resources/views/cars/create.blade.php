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

                <form method="POST" action="{{ route('cars.store') }}" enctype="multipart/form-data" id="carForm">
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

                        <!-- قیمت خرید با ویرگول -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">قیمت خرید (ریال) <span class="text-red-500">*</span></label>
                            <input type="text" name="purchase_price" id="purchase_price" value="{{ old('purchase_price') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('purchase_price') border-red-500 @enderror" 
                                   placeholder="مثال: ۴۵۰,۰۰۰,۰۰۰" min="0" required>
                            @error('purchase_price') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- تاریخ خرید (شمسی) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ خرید <span class="text-red-500">*</span></label>
                            <input type="text" name="purchase_date" id="purchase_date" value="{{ old('purchase_date', $todayJalali ?? '') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('purchase_date') border-red-500 @enderror" 
                                   placeholder="مثال: 1402/05/15" autocomplete="off" required>
                            @error('purchase_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror>
                        </div>

                        {{-- بخش آپلود عکس‌ها --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">تصاویر خودرو</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-500 transition" id="dropzone">
                                <input type="file" name="images[]" id="images" multiple accept="image/*" class="hidden" onchange="previewImages(this)">
                                <div class="cursor-pointer" onclick="document.getElementById('images').click()">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600">
                                        برای آپلود کلیک کنید یا فایل‌ها را اینجا بکشید
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        فرمت‌های مجاز: JPEG, PNG, JPG, GIF (حداکثر ۲ مگابایت هر عکس)
                                    </p>
                                </div>
                            </div>
                            
                            {{-- پیش‌نمایش عکس‌ها --}}
                            <div id="preview-container" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4"></div>
                            
                            @error('images') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            @error('images.*') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
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

{{-- کتابخونه AutoNumeric برای فرمت اعداد --}}
<script src="https://cdn.jsdelivr.net/npm/autonumeric@4.6.0/dist/autoNumeric.min.js"></script>

<script>
    $(document).ready(function() {
        // تقویم شمسی
        $('#purchase_date').persianDatepicker({
            format: 'YYYY/MM/DD',
            autoClose: true,
            initialValue: true,
            calendar: { persian: true }
        });

        // فرمت قیمت خرید
        if ($('#purchase_price').length) {
            new AutoNumeric('#purchase_price', {
                digitGroupSeparator: ',',
                decimalCharacter: '.',
                decimalPlaces: 0,
                minimumValue: '0',
                maximumValue: '999999999999999',
                modifyValueOnWheel: false,
                unformatOnSubmit: true,
                allowDecimalPadding: false
            });
        }
    });

    // پیش‌نمایش عکس‌ها
    function previewImages(input) {
        const previewContainer = document.getElementById('preview-container');
        previewContainer.innerHTML = '';

        if (input.files) {
            for (let i = 0; i < input.files.length; i++) {
                const file = input.files[i];
                
                // بررسی حجم فایل (حداکثر ۲ مگابایت)
                if (file.size > 2 * 1024 * 1024) {
                    alert(`فایل ${file.name} بزرگتر از ۲ مگابایت است`);
                    continue;
                }

                const reader = new FileReader();
                const previewDiv = document.createElement('div');
                previewDiv.className = 'relative group';

                reader.onload = function(e) {
                    previewDiv.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg border-2 border-gray-300">
                        <button type="button" onclick="removeImage(this)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    `;
                }

                reader.readAsDataURL(file);
                previewContainer.appendChild(previewDiv);
            }
        }
    }

    function removeImage(button) {
        button.closest('.relative').remove();
        // ریست کردن input file (اختیاری - می‌تونی کاملترش کنی)
        document.getElementById('images').value = '';
    }

    // درگ و دراپ ساده
    const dropzone = document.getElementById('dropzone');
    
    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzone.classList.add('border-blue-500', 'bg-blue-50');
    });

    dropzone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropzone.classList.remove('border-blue-500', 'bg-blue-50');
    });

    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.classList.remove('border-blue-500', 'bg-blue-50');
        
        const files = e.dataTransfer.files;
        document.getElementById('images').files = files;
        previewImages(document.getElementById('images'));
    });
</script>
@endpush