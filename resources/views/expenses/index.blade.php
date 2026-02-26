@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">مدیریت هزینه‌ها</h2>
                    
                    <a href="{{ route('expenses.create') }}" 
                       class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-lg transition">
                        <svg class="h-5 w-5 inline ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        ثبت هزینه جدید
                    </a>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- خلاصه آماری -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-orange-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">کل هزینه‌ها</div>
                        <div class="text-2xl font-bold text-orange-600">{{ number_format($totalExpenses) }} ریال</div>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">هزینه‌های خودرو</div>
                        <div class="text-2xl font-bold text-blue-600">{{ number_format($carExpenses) }} ریال</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600">هزینه‌های عمومی</div>
                        <div class="text-2xl font-bold text-gray-600">{{ number_format($generalExpenses) }} ریال</div>
                    </div>
                </div>

                <!-- فیلترها -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <form method="GET" action="{{ route('expenses.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">دسته‌بندی</label>
                            <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <option value="">همه</option>
                                <option value="car_service" {{ request('category') == 'car_service' ? 'selected' : '' }}>خدمات خودرو</option>
                                <option value="car_repair" {{ request('category') == 'car_repair' ? 'selected' : '' }}>تعمیرات</option>
                                <option value="car_wash" {{ request('category') == 'car_wash' ? 'selected' : '' }}>کارواش</option>
                                <option value="fuel" {{ request('category') == 'fuel' ? 'selected' : '' }}>سوخت</option>
                                <option value="rent" {{ request('category') == 'rent' ? 'selected' : '' }}>اجاره</option>
                                <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>سایر</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition">
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
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">دسته‌بندی</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">خودرو</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">مبلغ</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">حساب</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">عملیات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($expenses as $index => $expense)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $expenses->firstItem() + $index }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $expense->expense_date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $expense->title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $expense->category_label }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($expense->car)
                                        <a href="{{ route('cars.show', $expense->car) }}" class="text-blue-600 hover:underline">
                                            {{ $expense->car->title }}
                                        </a>
                                    @else
                                        <span class="text-gray-500">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-orange-600">{{ number_format($expense->amount) }} ریال</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $expense->account->name ?? '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('expenses.show', $expense) }}" class="text-blue-600 hover:text-blue-900" title="نمایش">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('expenses.edit', $expense) }}" class="text-green-600 hover:text-green-900" title="ویرایش">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="inline" onsubmit="return confirm('آیا از حذف این هزینه اطمینان دارید؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="حذف">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                    هیچ هزینه‌ای ثبت نشده است.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $expenses->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection