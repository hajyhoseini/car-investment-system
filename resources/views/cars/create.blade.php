@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold mb-6">افزودن خودرو جدید</h2>

                <form method="POST" action="{{ route('cars.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- عنوان -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">عنوان</label>
                            <input type="text" name="title" value="{{ old('title') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- برند -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">برند</label>
                            <input type="text" name="brand" value="{{ old('brand') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('brand') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- مدل -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">مدل</label>
                            <input type="text" name="model" value="{{ old('model') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('model') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- سال ساخت -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">سال ساخت</label>
                            <input type="number" name="year" value="{{ old('year') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('year') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- کارکرد -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">کارکرد (کیلومتر)</label>
                            <input type="number" name="kilometers" value="{{ old('kilometers') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('kilometers') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- نوع سوخت -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">نوع سوخت</label>
                            <select name="fuel_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="بنزین">بنزین</option>
                                <option value="گازوئیل">گازوئیل</option>
                                <option value="هیبرید">هیبرید</option>
                                <option value="برقی">برقی</option>
                            </select>
                            @error('fuel_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- گیربکس -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">گیربکس</label>
                            <select name="transmission" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="دنده‌ای">دنده‌ای</option>
                                <option value="اتوماتیک">اتوماتیک</option>
                            </select>
                            @error('transmission') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- رنگ -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">رنگ</label>
                            <input type="text" name="color" value="{{ old('color') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('color') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- قیمت خرید -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">قیمت خرید (ریال)</label>
                            <input type="number" name="purchase_price" value="{{ old('purchase_price') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('purchase_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- تاریخ خرید -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">تاریخ خرید</label>
                            <input type="date" name="purchase_date" value="{{ old('purchase_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('purchase_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- توضیحات (تمام عرض) -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">توضیحات</label>
                            <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description') }}</textarea>
                            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            ذخیره خودرو
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection