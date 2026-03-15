@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">ویرایش تعهد</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('liabilities.show', $liability) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            نمایش جزئیات
                        </a>
                        <a href="{{ route('liabilities.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
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

                <form method="POST" action="{{ route('liabilities.update', $liability) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- نوع تعهد -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">نوع تعهد <span class="text-red-500">*</span></label>
                            <select name="type" id="type" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition @error('type') border-red-500 @enderror" required>
                                <option value="debt" {{ old('type', $liability->type) == 'debt' ? 'selected' : '' }}>بدهی</option>
                                <option value="check" {{ old('type', $liability->type) == 'check' ? 'selected' : '' }}>چک</option>
                                <option value="installment" {{ old('type', $liability->type) == 'installment' ? 'selected' : '' }}>قسط</option>
                            </select>
                            @error('type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- شخص مرتبط (جایگزین نام طلبکار) -->
<!-- شخص مرتبط (جایگزین نام طلبکار) -->
<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">شخص مرتبط</label>
    <select name="person_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition @error('person_id') border-red-500 @enderror">
        <option value="">بدون شخص</option>
        @foreach($formattedPeople as $person)
            <option value="{{ $person['id'] }}" 
                {{ old('person_id', $liability->person_id) == $person['id'] ? 'selected' : '' }}>
                {{ $person['text'] }} 
                @if(!empty($person['subtext']))
                    <span class="text-gray-500 text-xs">({{ $person['subtext'] }})</span>
                @endif
            </option>
        @endforeach
    </select>
    @error('person_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
</div>

                        <!-- مبلغ کل -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">مبلغ کل (ریال) <span class="text-red-500">*</span></label>
                            <input type="number" name="amount" id="amount" value="{{ old('amount', $liability->amount) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition @error('amount') border-red-500 @enderror" 
                                   required>
                            @error('amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- مبلغ باقی‌مانده -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">مبلغ باقی‌مانده (ریال) <span class="text-red-500">*</span></label>
                            <input type="number" name="remaining_amount" id="remaining_amount" value="{{ old('remaining_amount', $liability->remaining_amount) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition @error('remaining_amount') border-red-500 @enderror" 
                                   required>
                            @error('remaining_amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- تاریخ سررسید (شمسی) -->
<!-- تاریخ سررسید (شمسی) -->
<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ سررسید <span class="text-red-500">*</span></label>
    <input type="text" name="due_date" id="due_date" 
           value="{{ old('due_date', $liability->due_date_jalali) }}" 
           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition" 
           placeholder="مثال: 1402/12/25" autocomplete="off" required>
    @error('due_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
</div>

                        <!-- وضعیت -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">وضعیت <span class="text-red-500">*</span></label>
                            <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition @error('status') border-red-500 @enderror" required>
                                <option value="pending" {{ old('status', $liability->status) == 'pending' ? 'selected' : '' }}>در انتظار</option>
                                <option value="paid" {{ old('status', $liability->status) == 'paid' ? 'selected' : '' }}>پرداخت شده</option>
                                <option value="overdue" {{ old('status', $liability->status) == 'overdue' ? 'selected' : '' }}>سررسید گذشته</option>
                            </select>
                            @error('status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- توضیحات -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات</label>
                            <textarea name="description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition @error('description') border-red-500 @enderror">{{ old('description', $liability->description) }}</textarea>
                            @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- اطلاعات اضافی -->
                    <div class="mt-8 p-4 bg-gray-50 rounded-xl">
                        <h3 class="text-lg font-semibold mb-4 text-red-600">اطلاعات ثبت</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <span class="text-sm text-gray-600">تاریخ ایجاد:</span>
                                <span class="block font-medium">{{ jalali_datetime($liability->created_at) }}</span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">آخرین به‌روزرسانی:</span>
                                <span class="block font-medium">{{ jalali_datetime($liability->updated_at) }}</span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">وضعیت سررسید:</span>
                                <span class="block font-medium">
                                    @if($liability->isOverdue())
                                        <span class="text-red-600">⚠️ سررسید گذشته</span>
                                    @else
                                        <span class="text-green-600">✅ در محدوده زمانی</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 space-x-2 rtl:space-x-reverse">
                        <a href="{{ route('liabilities.index') }}" class="px-6 py-3 bg-gray-500 hover:bg-gray-700 text-white font-bold rounded-xl transition">
                            انصراف
                        </a>
                        <button type="submit" class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition transform hover:scale-105">
                            به‌روزرسانی تعهد
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
        $('#due_date').persianDatepicker({
            format: 'YYYY/MM/DD',
            autoClose: true,
            initialValue: true,
            calendar: {
                persian: true
            }
        });
    });

    document.getElementById('amount').addEventListener('input', function() {
        const amount = parseFloat(this.value) || 0;
        const remaining = document.getElementById('remaining_amount');
        
        // اگه remaining_amount خالی بود یا از amount بیشتر بود، مقدار پیش‌فرض رو برابر amount قرار بده
        if (!remaining.value || parseFloat(remaining.value) > amount) {
            remaining.value = amount;
        }
    });

    // اطمینان از اینکه remaining_amount بیشتر از amount نباشه
    document.getElementById('remaining_amount').addEventListener('input', function() {
        const amount = parseFloat(document.getElementById('amount').value) || 0;
        const remaining = parseFloat(this.value) || 0;
        
        if (remaining > amount) {
            this.value = amount;
            alert('مبلغ باقی‌مانده نمی‌تواند از مبلغ کل بیشتر باشد.');
        }
    });
</script>
@endpush