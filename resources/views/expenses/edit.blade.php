@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">ویرایش هزینه</h2>
                    <a href="{{ route('expenses.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
                        بازگشت
                    </a>
                </div>

                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('expenses.update', $expense) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- عنوان -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">عنوان <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $expense->title) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition" required>
                        </div>

                        <!-- مبلغ -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">مبلغ (ریال) <span class="text-red-500">*</span></label>
                            <input type="number" name="amount" value="{{ old('amount', $expense->amount) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition" min="1000" required>
                        </div>

                        <!-- تاریخ هزینه -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ هزینه <span class="text-red-500">*</span></label>
                            <input type="date" name="expense_date" value="{{ old('expense_date', $expense->expense_date) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition" required>
                        </div>

                        <!-- دسته‌بندی -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">دسته‌بندی <span class="text-red-500">*</span></label>
                            <select name="category" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition" required>
                                @foreach($categories as $key => $label)
                                    <option value="{{ $key }}" {{ old('category', $expense->category) == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- خودرو مرتبط -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">خودرو مرتبط</label>
                            <select name="car_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                                <option value="">بدون خودرو (هزینه عمومی)</option>
                                @foreach($cars as $car)
                                    <option value="{{ $car->id }}" {{ old('car_id', $expense->car_id) == $car->id ? 'selected' : '' }}>
                                        {{ $car->title }} - {{ $car->brand }} {{ $car->model }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- حساب پرداخت -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">حساب پرداخت</label>
                            <select name="account_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                                <option value="">بدون کسر از حساب</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}" {{ old('account_id', $expense->account_id) == $account->id ? 'selected' : '' }}>
                                        {{ $account->name }} (موجودی: {{ number_format($account->amount) }} ریال)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- روش پرداخت -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">روش پرداخت</label>
                            <select name="payment_method_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                                <option value="">انتخاب کنید</option>
                                @foreach($paymentMethods as $method)
                                    <option value="{{ $method->id }}" {{ old('payment_method_id', $expense->payment_method_id) == $method->id ? 'selected' : '' }}>
                                        {{ $method->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- تصویر رسید جدید -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">تصویر رسید جدید</label>
                            <input type="file" name="receipt_image" accept="image/*"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                            @if($expense->receipt_image)
                                <p class="text-xs text-gray-500 mt-1">تصویر فعلی: <a href="{{ asset('storage/' . $expense->receipt_image) }}" target="_blank" class="text-blue-600 hover:underline">مشاهده</a></p>
                            @endif
                        </div>

                        <!-- توضیحات -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات</label>
                            <textarea name="description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">{{ old('description', $expense->description) }}</textarea>
                        </div>

                        <!-- یادداشت‌ها -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">یادداشت‌ها</label>
                            <textarea name="notes" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">{{ old('notes', $expense->notes) }}</textarea>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 space-x-2">
                        <a href="{{ route('expenses.index') }}" class="px-6 py-3 bg-gray-500 hover:bg-gray-700 text-white font-bold rounded-xl transition">
                            انصراف
                        </a>
                        <button type="submit" class="px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl shadow-lg transition">
                            بروزرسانی هزینه
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection