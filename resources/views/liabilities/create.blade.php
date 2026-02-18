@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold mb-6">ثبت تعهد جدید</h2>

                <form method="POST" action="{{ route('liabilities.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- نوع تعهد -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">نوع تعهد <span class="text-red-500">*</span></label>
                            <select name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">انتخاب کنید</option>
                                <option value="debt" {{ old('type') == 'debt' ? 'selected' : '' }}>بدهی</option>
                                <option value="check" {{ old('type') == 'check' ? 'selected' : '' }}>چک</option>
                                <option value="installment" {{ old('type') == 'installment' ? 'selected' : '' }}>قسط</option>
                            </select>
                            @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- نام طلبکار -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">نام طلبکار <span class="text-red-500">*</span></label>
                            <input type="text" name="creditor_name" value="{{ old('creditor_name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            @error('creditor_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- مبلغ کل -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">مبلغ کل (ریال) <span class="text-red-500">*</span></label>
                            <input type="number" name="amount" id="amount" value="{{ old('amount') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- مبلغ باقی‌مانده -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">مبلغ باقی‌مانده (ریال) <span class="text-red-500">*</span></label>
                            <input type="number" name="remaining_amount" id="remaining_amount" value="{{ old('remaining_amount') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            @error('remaining_amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- تاریخ سررسید -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">تاریخ سررسید <span class="text-red-500">*</span></label>
                            <input type="date" name="due_date" value="{{ old('due_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            @error('due_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- وضعیت -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">وضعیت <span class="text-red-500">*</span></label>
                            <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>در انتظار</option>
                                <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>پرداخت شده</option>
                                <option value="overdue" {{ old('status') == 'overdue' ? 'selected' : '' }}>سررسید گذشته</option>
                            </select>
                            @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- توضیحات -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">توضیحات</label>
                            <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description') }}</textarea>
                            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 space-x-2 rtl:space-x-reverse">
                        <a href="{{ route('liabilities.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition">
                            انصراف
                        </a>
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition">
                            ثبت تعهد
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('amount').addEventListener('input', function() {
    const amount = parseFloat(this.value) || 0;
    const remaining = document.getElementById('remaining_amount');
    
    // اگر remaining_amount خالی بود، مقدار پیش‌فرض رو برابر amount قرار بده
    if (!remaining.value) {
        remaining.value = amount;
    }
});
</script>
@endpush
@endsection