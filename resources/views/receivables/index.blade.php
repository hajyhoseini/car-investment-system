@extends('layouts.app')

@section('styles')
<style>
    /* استایل برای اینپوت‌های قیمت */
    .price-input {
        text-align: left;
        direction: ltr;
    }
    
    /* استایل برای dropdown فیلتر */
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

                <!-- فیلترها با تاریخ شمسی -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <form method="GET" action="{{ route('receivables.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">از تاریخ</label>
                            <input type="text" name="start_date" id="start_date" value="{{ request('start_date') }}" 
                                   class="jalali-datepicker w-full px-3 py-2 border border-gray-300 rounded-lg"
                                   placeholder="مثال: ۱۴۰۲/۰۱/۰۱" autocomplete="off">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">تا تاریخ</label>
                            <input type="text" name="end_date" id="end_date" value="{{ request('end_date') }}" 
                                   class="jalali-datepicker w-full px-3 py-2 border border-gray-300 rounded-lg"
                                   placeholder="مثال: ۱۴۰۲/۱۲/۲۹" autocomplete="off">
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
                                    <div class="flex items-center space-x-2 space-x-reverse">
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
                                        <a href="#" onclick="openPaymentModal({{ $receivable->id }}, {{ $receivable->remaining_amount }})" class="text-purple-600 hover:text-purple-900" title="ثبت پرداخت">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                        </a>
                                        @endif
                                        <button type="button" 
                                                onclick="confirmDelete({{ $receivable->id }})"
                                                class="text-red-600 hover:text-red-800 transition"
                                                title="حذف">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
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

<!-- مودال ثبت پرداخت با کامپوننت searchable-select -->
<div id="paymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-md w-full">
        <h3 class="text-xl font-bold mb-4">ثبت پرداخت جدید</h3>
        <form id="paymentForm" method="POST" action="">
            @csrf
            <input type="hidden" id="receivable_id" name="receivable_id">
            
            <!-- مبلغ پرداخت با فرمت ویرگول -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">مبلغ پرداخت (ریال) <span class="text-red-500">*</span></label>
                <input type="text" name="payment_amount_display" id="payment_amount_display" 
                       class="price-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" 
                       placeholder="مثال: ۱,۰۰۰,۰۰۰" required>
                <input type="hidden" name="payment_amount" id="payment_amount" value="">
                <p class="text-xs text-gray-500 mt-1" id="remainingAmountInfo">حداکثر مبلغ قابل پرداخت: ۰ ریال</p>
            </div>
            
            <!-- تاریخ پرداخت شمسی -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ پرداخت <span class="text-red-500">*</span></label>
                <input type="text" name="payment_date" id="payment_date" 
                       value="{{ now_jalali('Y/m/d') }}" 
                       class="jalali-datepicker w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                       placeholder="مثال: ۱۴۰۲/۱۲/۲۵" autocomplete="off" required>
            </div>
            
            <!-- روش پرداخت با کامپوننت searchable-select -->
            <div class="mb-4">
                <x-searchable-select 
                    name="payment_method"
                    label="روش پرداخت"
                    :options="[
                        ['id' => 'cash', 'text' => 'نقدی'],
                        ['id' => 'card', 'text' => 'کارت به کارت'],
                        ['id' => 'check', 'text' => 'چک'],
                        ['id' => 'transfer', 'text' => 'حواله']
                    ]"
                    placeholder="انتخاب کنید..."
                    required="true"
                />
            </div>
            
            <!-- یادداشت -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">یادداشت</label>
                <textarea name="payment_notes" rows="3" 
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"
                          placeholder="یادداشت..."></textarea>
            </div>
            
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closePaymentModal()" 
                        class="px-4 py-2 bg-gray-500 hover:bg-gray-700 text-white rounded-lg transition">
                    انصراف
                </button>
                <button type="submit" onclick="return handlePaymentSubmit()"
                        class="px-4 py-2 bg-purple-500 hover:bg-purple-700 text-white rounded-lg transition">
                    ثبت پرداخت
                </button>
            </div>
        </form>
    </div>
</div>

<!-- مودال تأیید حذف -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 transform transition-all">
        <div class="p-6">
            <div class="flex items-center justify-center mb-4">
                <div class="bg-red-100 rounded-full p-3">
                    <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
            <h3 class="text-lg font-bold text-gray-900 text-center mb-2">تأیید حذف</h3>
            <p class="text-gray-600 text-center mb-6">
                آیا از حذف این مطالبه اطمینان دارید؟ این عملیات غیرقابل بازگشت است.
            </p>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()"
                        class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition font-medium">
                    انصراف
                </button>
                <form id="deleteForm" method="POST" action="" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition font-medium">
                        حذف
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

<script>
    let currentReceivableId = null;
    let currentRemainingAmount = 0;

    $(document).ready(function() {
        // تقویم شمسی برای فیلتر تاریخ‌ها
        $('.jalali-datepicker').persianDatepicker({
            format: 'YYYY/MM/DD',
            autoClose: true,
            initialValue: false,
            calendar: {
                persian: true
            }
        });

        // تقویم شمسی برای مودال
        $('#payment_date').persianDatepicker({
            format: 'YYYY/MM/DD',
            autoClose: true,
            initialValue: true,
            calendar: {
                persian: true
            }
        });

        // فرمت کردن مبلغ با ویرگول
        $('.price-input').on('input', function() {
            let value = this.value.replace(/[^\d]/g, '');
            if (value) {
                this.value = Number(value).toLocaleString('en-US');
            }
        });
    });

    function openPaymentModal(receivableId, remainingAmount) {
        currentReceivableId = receivableId;
        currentRemainingAmount = remainingAmount;
        
        // آپدیت متن حداکثر مبلغ
        document.getElementById('remainingAmountInfo').textContent = 
            `حداکثر مبلغ قابل پرداخت: ${Number(remainingAmount).toLocaleString('fa-IR')} ریال`;
        
        // نمایش مودال
        document.getElementById('paymentModal').classList.remove('hidden');
        document.getElementById('paymentModal').classList.add('flex');
        document.getElementById('receivable_id').value = receivableId;
        document.getElementById('paymentForm').action = `/receivables/${receivableId}/payment`;
        
        // پاک کردن مقدار قبلی
        document.getElementById('payment_amount_display').value = '';
        document.getElementById('payment_amount').value = '';
        
        // جلوگیری از اسکرول صفحه
        document.body.style.overflow = 'hidden';
    }
    
    function closePaymentModal() {
        document.getElementById('paymentModal').classList.add('hidden');
        document.getElementById('paymentModal').classList.remove('flex');
        
        // برگردوندن اسکرول
        document.body.style.overflow = 'auto';
    }

    function handlePaymentSubmit() {
        const displayInput = document.getElementById('payment_amount_display');
        const hiddenInput = document.getElementById('payment_amount');
        
        // گرفتن مقدار عددی (حذف کاما)
        let numericValue = displayInput.value.replace(/[^\d]/g, '');
        
        if (!numericValue) {
            alert('لطفاً مبلغ پرداخت را وارد کنید');
            return false;
        }
        
        numericValue = parseInt(numericValue);
        
        // validation
        if (numericValue < 1000) {
            alert('مبلغ پرداخت باید حداقل ۱۰۰۰ ریال باشد');
            return false;
        }
        
        if (numericValue > currentRemainingAmount) {
            alert(`مبلغ پرداخت نمی‌تواند از ${currentRemainingAmount.toLocaleString('fa-IR')} ریال بیشتر باشد`);
            return false;
        }
        
        // ست کردن مقدار در hidden input
        hiddenInput.value = numericValue;
        
        return true;
    }

    function confirmDelete(receivableId) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        form.action = '/receivables/' + receivableId;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // بستن مودال پرداخت با کلیک روی پس‌زمینه
    document.getElementById('paymentModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePaymentModal();
        }
    });

    // بستن مودال حذف با کلیک روی پس‌زمینه
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });

    // بستن مودال‌ها با کلید Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const paymentModal = document.getElementById('paymentModal');
            if (!paymentModal.classList.contains('hidden')) {
                closePaymentModal();
            }
            const deleteModal = document.getElementById('deleteModal');
            if (!deleteModal.classList.contains('hidden')) {
                closeDeleteModal();
            }
        }
    });
</script>
@endpush