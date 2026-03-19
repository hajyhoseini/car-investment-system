@extends('layouts.app')

@section('styles')
<style>
    .filter-dropdown {
        min-width: 250px;
    }
</style>
@endsection

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

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- فیلترها -->
            <!-- فیلترها -->
<div class="mb-6 p-4 bg-gray-50 rounded-lg">
    <form method="GET" action="{{ route('transactions.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
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
        
        <!-- فیلتر شخص با کامپوننت searchable-select -->
        <div class="filter-dropdown">
            <x-searchable-select 
                name="person_id"
                label="شخص"
                :options="$personOptions"
                :selected="request('person_id')"
                placeholder="همه اشخاص"
            />
        </div>
        
        <div class="md:col-span-5 flex justify-end gap-2">
            <a href="{{ route('transactions.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg transition">
                حذف فیلترها
            </a>
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
                            @forelse($transactions as $transaction)
                            <tr class="hover:bg-gray-50">
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
                                <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->fromAsset->name ?? '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->toAsset->name ?? '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($transaction->person)
                                        <a href="{{ route('people.show', $transaction->person) }}" class="text-blue-600 hover:underline">
                                            {{ $transaction->person->full_name }}
                                        </a>
                                    @else
                                        <span class="text-gray-500">—</span>
                                    @endif
                                </td>
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
                                    <div class="flex items-center space-x-3 rtl:space-x-reverse">
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
                                        <button onclick="confirmDelete({{ $transaction->id }}, '{{ $transaction->transaction_number }}')" 
                                                class="text-red-600 hover:text-red-900" title="حذف">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="mt-4 text-lg">هیچ تراکنشی یافت نشد</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $transactions->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- مودال تأیید حذف -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">تأیید حذف</h3>
            <p class="text-sm text-gray-500 mb-4">
                آیا از حذف تراکنش <span id="transactionNumber" class="font-bold text-red-600"></span> اطمینان دارید؟
            </p>
            <p class="text-xs text-red-500 mb-4">
                توجه: در صورت تکمیل بودن تراکنش، موجودی حساب نیز اصلاح خواهد شد.
            </p>
            <div class="flex justify-center gap-3">
                <button onclick="closeDeleteModal()" 
                        class="px-4 py-2 bg-gray-500 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition">
                    انصراف
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 hover:bg-red-800 text-white text-sm font-medium rounded-lg transition">
                        بله، حذف کن
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(transactionId, transactionNumber) {
        document.getElementById('transactionNumber').textContent = transactionNumber;
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = '{{ url("transactions") }}/' + transactionId;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    window.onclick = function(event) {
        const modal = document.getElementById('deleteModal');
        if (event.target == modal) {
            closeDeleteModal();
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
    });
</script>
@endpush