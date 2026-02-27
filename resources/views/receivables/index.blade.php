@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">مدیریت مطالبات</h2>
                    
                    <a href="{{ route('receivables.create') }}" 
                       class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition">
                        <svg class="h-5 w-5 inline ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        ثبت مطالبه جدید
                    </a>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- خلاصه آماری -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">کل مطالبات</div>
                        <div class="text-2xl font-bold text-purple-600">{{ number_format($totalAmount) }} ریال</div>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">پرداخت شده</div>
                        <div class="text-2xl font-bold text-green-600">{{ number_format($totalPaid) }} ریال</div>
                    </div>
                    <div class="bg-orange-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">باقی‌مانده</div>
                        <div class="text-2xl font-bold text-orange-600">{{ number_format($totalRemaining) }} ریال</div>
                    </div>
                    <div class="bg-red-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">سررسید گذشته</div>
                        <div class="text-2xl font-bold text-red-600">{{ $overdueCount }} مورد</div>
                    </div>
                </div>

                <!-- فیلترها -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <form method="GET" action="{{ route('receivables.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
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
                            <select name="currency_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <option value="">همه</option>
                                <option value="cash" {{ request('currency_type') == 'cash' ? 'selected' : '' }}>نقد</option>
                                <option value="check" {{ request('currency_type') == 'check' ? 'selected' : '' }}>چک</option>
                                <option value="gold" {{ request('currency_type') == 'gold' ? 'selected' : '' }}>طلا</option>
                                <option value="dollar" {{ request('currency_type') == 'dollar' ? 'selected' : '' }}>دلار</option>
                                <option value="other" {{ request('currency_type') == 'other' ? 'selected' : '' }}>سایر</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">وضعیت</label>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <option value="">همه</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>در انتظار</option>
                                <option value="partially_paid" {{ request('status') == 'partially_paid' ? 'selected' : '' }}>پرداخت جزئی</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>تسویه شده</option>
                                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>سررسید گذشته</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition w-full">
                                اعمال فیلتر
                            </button>
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">#</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاریخ</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">عنوان</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">نوع</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">شخص</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">مبلغ کل</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">پرداخت شده</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">باقی‌مانده</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">سررسید</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">وضعیت</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">عملیات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($receivables as $index => $receivable)
                            <tr class="hover:bg-gray-50 transition {{ $receivable->status == 'overdue' ? 'bg-red-50' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $receivables->firstItem() + $index }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $receivable->receivable_date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $receivable->title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $receivable->currency_type_label }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($receivable->person)
                                        <a href="{{ route('people.show', $receivable->person) }}" class="text-blue-600 hover:underline">
                                            {{ $receivable->person->full_name }}
                                        </a>
                                    @else
                                        <span class="text-gray-500">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold">{{ number_format($receivable->amount) }} ریال</td>
                                <td class="px-6 py-4 whitespace-nowrap text-green-600">{{ number_format($receivable->paid_amount) }} ریال</td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold {{ $receivable->remaining_amount > 0 ? 'text-orange-600' : 'text-green-600' }}">
                                    {{ number_format($receivable->remaining_amount) }} ریال
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($receivable->due_date)
                                        {{ $receivable->due_date }}
                                        @if($receivable->status == 'overdue')
                                            <span class="text-red-600 text-xs block">(سررسید گذشته)</span>
                                        @endif
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 rounded-full text-xs 
                                        @if($receivable->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($receivable->status == 'partially_paid') bg-blue-100 text-blue-800
                                        @elseif($receivable->status == 'paid') bg-green-100 text-green-800
                                        @elseif($receivable->status == 'overdue') bg-red-100 text-red-800
                                        @endif">
                                        {{ $receivable->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('receivables.show', $receivable) }}" class="text-blue-600 hover:text-blue-900" title="نمایش">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('receivables.edit', $receivable) }}" class="text-green-600 hover:text-green-900" title="ویرایش">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        @if($receivable->remaining_amount > 0)
                                        <a href="#" onclick="openPaymentModal({{ $receivable->id }})" class="text-purple-600 hover:text-purple-900" title="ثبت پرداخت">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="px-6 py-4 text-center text-gray-500">
                                    هیچ مطالبه‌ای ثبت نشده است.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $receivables->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- مودال ثبت پرداخت -->
<div id="paymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-md w-full">
        <h3 class="text-xl font-bold mb-4">ثبت پرداخت جدید</h3>
        <form id="paymentForm" method="POST" action="">
            @csrf
            <input type="hidden" id="receivable_id" name="receivable_id">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">مبلغ پرداخت (ریال)</label>
                <input type="number" name="payment_amount" id="payment_amount" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" 
                       min="1000" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ پرداخت</label>
                <input type="date" name="payment_date" id="payment_date" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" 
                       value="{{ now()->format('Y-m-d') }}" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">روش پرداخت</label>
                <select name="payment_method" id="payment_method" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" required>
                    <option value="">انتخاب کنید</option>
                    <option value="cash">نقدی</option>
                    <option value="card">کارت به کارت</option>
                    <option value="check">چک</option>
                    <option value="transfer">حواله</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">یادداشت</label>
                <textarea name="payment_notes" rows="3" 
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"></textarea>
            </div>
            
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closePaymentModal()" 
                        class="px-4 py-2 bg-gray-500 hover:bg-gray-700 text-white rounded-lg transition">
                    انصراف
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-purple-500 hover:bg-purple-700 text-white rounded-lg transition">
                    ثبت پرداخت
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openPaymentModal(receivableId) {
        document.getElementById('paymentModal').classList.remove('hidden');
        document.getElementById('paymentModal').classList.add('flex');
        document.getElementById('receivable_id').value = receivableId;
        document.getElementById('paymentForm').action = `/receivables/${receivableId}/payment`;
    }
    
    function closePaymentModal() {
        document.getElementById('paymentModal').classList.add('hidden');
        document.getElementById('paymentModal').classList.remove('flex');
    }
</script>
@endpush
@endsection