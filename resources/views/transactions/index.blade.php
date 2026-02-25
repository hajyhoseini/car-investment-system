@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">لیست تراکنش‌ها</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('transactions.create', ['type' => 'income']) }}" 
                           class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            دریافت جدید
                        </a>
                        <a href="{{ route('transactions.create', ['type' => 'expense']) }}" 
                           class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            پرداخت جدید
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- فیلترها -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <form method="GET" action="{{ route('transactions.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">از تاریخ</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">تا تاریخ</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">نوع</label>
                            <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <option value="">همه</option>
                                <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>دریافت</option>
                                <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>پرداخت</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">وضعیت</label>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <option value="">همه</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>در انتظار</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>تکمیل شده</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>لغو شده</option>
                            </select>
                        </div>
                        <div class="md:col-span-4 flex justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition">
                                اعمال فیلتر
                            </button>
                        </div>
                    </form>
                </div>

                <!-- خلاصه آماری -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">کل دریافتی</div>
                        <div class="text-2xl font-bold text-green-600">{{ number_format($totalIncome ?? 0) }} ریال</div>
                    </div>
                    <div class="bg-red-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">کل پرداختی</div>
                        <div class="text-2xl font-bold text-red-600">{{ number_format($totalExpense ?? 0) }} ریال</div>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">دریافتی امروز</div>
                        <div class="text-2xl font-bold text-blue-600">{{ number_format($todayIncome ?? 0) }} ریال</div>
                    </div>
                    <div class="bg-orange-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">پرداختی امروز</div>
                        <div class="text-2xl font-bold text-orange-600">{{ number_format($todayExpense ?? 0) }} ریال</div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">شماره</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاریخ</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">نوع</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">مبلغ</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">حساب مبدأ</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">حساب مقصد</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">شخص</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">وضعیت</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">عملیات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($transactions as $transaction)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->transaction_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->transaction_date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 rounded-full text-xs 
                                        @if($transaction->type == 'income') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ $transaction->type_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold">{{ number_format($transaction->amount) }} ریال</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->fromAccount->name ?? '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->toAccount->name ?? '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->person->full_name ?? '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 rounded-full text-xs 
                                        @if($transaction->status == 'completed') bg-green-100 text-green-800
                                        @elseif($transaction->status == 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ $transaction->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('transactions.show', $transaction) }}" class="text-blue-600 hover:text-blue-900" title="نمایش">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('transactions.edit', $transaction) }}" class="text-green-600 hover:text-green-900" title="ویرایش">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection