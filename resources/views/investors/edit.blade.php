@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">ویرایش سرمایه‌گذار: {{ $investor->person?->display_name ?? 'نامشخص' }}</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('investors.show', $investor) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            نمایش جزئیات
                        </a>
                        <a href="{{ route('investors.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
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

                <form method="POST" action="{{ route('investors.update', $investor) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- انتخاب شخص با کامپوننت searchable-select -->
                        <div class="md:col-span-2">
                            <x-searchable-select 
                                name="person_id"
                                label="انتخاب شخص"
                                :options="$formattedPeople"
                                :selected="old('person_id', $investor->person_id)"
                                placeholder="جستجوی شخص..."
                                required="true"
                            />
                            <p class="text-xs text-gray-500 mt-1">با تغییر شخص، اطلاعات سرمایه‌گذار به شخص جدید متصل می‌شود</p>
                            <div class="mt-2 text-sm">
                                <a href="{{ route('people.create') }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">
                                    + ایجاد شخص جدید
                                </a>
                            </div>
                        </div>

                        <!-- توضیحات -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات</label>
                            <textarea name="description" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('description') border-red-500 @enderror" 
                                      placeholder="توضیحات اضافی...">{{ old('description', $investor->description) }}</textarea>
                            @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- اطلاعات تکمیلی شخص انتخاب شده -->
                    <div id="person_info" class="mt-6 p-4 bg-gray-50 rounded-xl {{ $investor->person_id ? '' : 'hidden' }}">
                        <h4 class="font-bold text-md mb-3 text-green-600">اطلاعات شخص انتخاب شده:</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">نام و نام خانوادگی:</span>
                                <span id="info_full_name" class="font-medium mr-1 block mt-1">{{ $investor->person?->full_name ?? '---' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">کد ملی:</span>
                                <span id="info_national_code" class="font-medium mr-1 block mt-1">{{ $investor->person?->national_code ?? '---' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">تلفن:</span>
                                <span id="info_phone" class="font-medium mr-1 block mt-1">{{ $investor->person?->phone ?? '---' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">نوع شخص:</span>
                                <span id="info_type" class="font-medium mr-1 block mt-1">{{ $investor->person?->type_label ?? '---' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">ایمیل:</span>
                                <span id="info_email" class="font-medium mr-1 block mt-1">{{ $investor->person?->email ?? '---' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">آدرس:</span>
                                <span id="info_address" class="font-medium mr-1 block mt-1">{{ $investor->person?->address ?? '---' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- خلاصه اطلاعات مالی -->
                    <div class="mt-8 p-4 bg-gray-50 rounded-xl border border-green-200">
                        <h3 class="text-lg font-semibold mb-4 text-green-600">اطلاعات مالی</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <span class="text-sm text-gray-600">کل سرمایه‌گذاری:</span>
                                <span class="block text-xl font-bold text-green-600">{{ number_format($investor->investments->sum('amount')) }} ریال</span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">تعداد سرمایه‌گذاری:</span>
                                <span class="block text-xl font-bold text-blue-600">{{ $investor->investments->count() }}</span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">آخرین سرمایه‌گذاری:</span>
                                <span class="block text-xl font-bold text-purple-600">
                                    {{ $investor->investments->max('created_at') ? \Carbon\Carbon::parse($investor->investments->max('created_at'))->format('Y/m/d') : '---' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 space-x-2 rtl:space-x-reverse">
                        <a href="{{ route('investors.index') }}" class="px-6 py-3 bg-gray-500 hover:bg-gray-700 text-white font-bold rounded-xl transition">
                            انصراف
                        </a>
                        <button type="submit" class="px-6 py-3 bg-green-500 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition transform hover:scale-105">
                            به‌روزرسانی سرمایه‌گذار
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // گوش دادن به تغییرات سلکت باکس (برای کامپوننت searchable-select)
    $(document).on('change', 'select[name="person_id"]', function() {
        var selected = $(this).find(':selected');
        var personId = $(this).val();
        
        if (personId) {
            // اطلاعات از data-attributes سلکت شده
            var fullName = selected.text().split('(')[0].trim();
            var nationalCode = selected.data('national-code');
            var phone = selected.data('phone');
            var type = selected.data('type-label');
            var email = selected.data('email');
            var address = selected.data('address');
            
            $('#info_full_name').text(fullName || '---');
            $('#info_national_code').text(nationalCode || '---');
            $('#info_phone').text(phone || '---');
            $('#info_type').text(type || '---');
            $('#info_email').text(email || '---');
            $('#info_address').text(address || '---');
            
            $('#person_info').removeClass('hidden');
        } else {
            $('#person_info').addClass('hidden');
        }
    });
});
</script>
@endpush