@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">نمایش اطلاعات کاربر</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            ویرایش کاربر
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            بازگشت به لیست
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-6 rounded-xl">
                        <h3 class="text-lg font-semibold mb-4 text-blue-600">اطلاعات شخصی</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-gray-600">نام و نام خانوادگی:</span>
                                <div class="text-lg font-medium">{{ $user->name }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">ایمیل:</span>
                                <div class="text-lg font-medium">{{ $user->email }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-xl">
                        <h3 class="text-lg font-semibold mb-4 text-green-600">نقش‌ها و دسترسی‌ها</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-gray-600">نقش‌ها:</span>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @foreach($user->roles as $role)
                                        <span class="px-3 py-1 rounded-full text-sm font-medium 
                                            @if($role->name == 'admin') bg-red-100 text-red-800
                                            @elseif($role->name == 'manager') bg-blue-100 text-blue-800
                                            @elseif($role->name == 'investor') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            @if($role->name == 'admin') ادمین
                                            @elseif($role->name == 'manager') مدیر
                                            @elseif($role->name == 'investor') سرمایه‌گذار
                                            @else {{ $role->name }}
                                            @endif
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-xl md:col-span-2">
                        <h3 class="text-lg font-semibold mb-4 text-purple-600">اطلاعات حساب</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <span class="text-sm text-gray-600">تاریخ ایجاد:</span>
                                <div class="text-base font-medium">{{ $user->created_at->format('Y/m/d H:i:s') }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">آخرین به‌روزرسانی:</span>
                                <div class="text-base font-medium">{{ $user->updated_at->format('Y/m/d H:i:s') }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">وضعیت ایمیل:</span>
                                <div class="text-base font-medium">
                                    @if($user->email_verified_at)
                                        <span class="text-green-600">تایید شده ({{ $user->email_verified_at->format('Y/m/d H:i') }})</span>
                                    @else
                                        <span class="text-red-600">تایید نشده</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection