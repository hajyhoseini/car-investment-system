@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">مدیریت حساب‌ها</h2>
                    
                    @can('create accounts')
                        <a href="{{ route('accounts.create') }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            <svg class="h-5 w-5 inline ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            افزودن حساب جدید
                        </a>
                    @endcan
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

                <!-- خلاصه آماری -->
                <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                    <div class="text-sm text-blue-700 font-medium">مجموع موجودی حساب‌ها</div>
                    <div class="text-2xl font-bold text-blue-800">{{ number_format($totalBalance) }} ریال</div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">#</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">نام حساب</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">نوع</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">بانک</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">شماره حساب</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">موجودی</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">وضعیت</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">عملیات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($accounts as $index => $account)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $accounts->firstItem() + $index }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $account->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $account->type_label }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $account->bank_name ?? '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $account->account_number ?? '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold">{{ number_format($account->balance) }} {{ $account->currency }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($account->is_active)
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">فعال</span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">غیرفعال</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('accounts.show', $account) }}" class="text-blue-600 hover:text-blue-900" title="نمایش">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('accounts.edit', $account) }}" class="text-green-600 hover:text-green-900" title="ویرایش">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('accounts.destroy', $account) }}" method="POST" class="inline" onsubmit="return confirm('آیا از حذف این حساب اطمینان دارید؟');">
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
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $accounts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection