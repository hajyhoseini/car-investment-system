@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- header پروفایل -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-2xl p-8 mb-8 text-white">
            <div class="flex flex-col md:flex-row items-center gap-8">
                <!-- عکس پروفایل دایره‌ای -->
                <div class="relative group">
                    <div class="w-32 h-32 rounded-full border-4 border-white overflow-hidden shadow-xl">
                        <img src="{{ Auth::user()->avatar_url }}" 
                             alt="avatar" 
                             class="w-full h-full object-cover"
                             id="avatarPreview">
                    </div>
                    <label for="avatarInput" class="absolute bottom-0 right-0 bg-white rounded-full p-2 shadow-lg cursor-pointer hover:bg-gray-100 transition transform hover:scale-110">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </label>
                </div>
                
                <!-- اطلاعات کاربری -->
                <div class="flex-1 text-center md:text-right">
                    <h1 class="text-3xl font-bold mb-2">{{ Auth::user()->name }}</h1>
                    <p class="text-blue-100">{{ Auth::user()->email }}</p>
                    <div class="flex gap-2 mt-4 justify-center md:justify-start">
                        @foreach(Auth::user()->getRoleNames() as $role)
                            <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm">
                                @if($role == 'admin') ادمین
                                @elseif($role == 'manager') مدیر
                                @elseif($role == 'investor') سرمایه‌گذار
                                @else {{ $role }}
                                @endif
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- فرم ویرایش پروفایل -->
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
            <div class="p-8">
                <h2 class="text-2xl font-bold mb-6">ویرایش اطلاعات</h2>

                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <!-- input مخفی برای آپلود عکس -->
                    <input type="file" id="avatarInput" name="avatar" accept="image/*" class="hidden" onchange="previewAvatar(this)">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- نام -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">نام و نام خانوادگی</label>
                            <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('name') border-red-500 @enderror"
                                   required>
                            @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- ایمیل -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ایمیل</label>
                            <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('email') border-red-500 @enderror"
                                   required>
                            @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- تلفن -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">شماره تلفن</label>
                            <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('phone') border-red-500 @enderror"
                                   placeholder="مثال: 09123456789">
                            @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- تاریخ عضویت (فقط نمایش) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ عضویت</label>
                            <div class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl text-gray-700">
                                {{ Auth::user()->created_at->format('Y/m/d') }}
                            </div>
                        </div>

                        <!-- بیوگرافی -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">بیوگرافی</label>
                            <textarea name="bio" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('bio') border-red-500 @enderror"
                                      placeholder="چند خط درباره خودتان بنویسید...">{{ old('bio', Auth::user()->bio) }}</textarea>
                            @error('bio') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold mb-4">تغییر رمز عبور</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- رمز عبور جدید -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">رمز عبور جدید</label>
                                <input type="password" name="password" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('password') border-red-500 @enderror"
                                       placeholder="فقط برای تغییر وارد کنید">
                                @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- تکرار رمز عبور -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">تکرار رمز عبور</label>
                                <input type="password" name="password_confirmation" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                       placeholder="تکرار رمز عبور جدید">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition transform hover:scale-105">
                            ذخیره تغییرات
                        </button>
                    </div>
                </form>

                <!-- بخش حذف حساب (فقط برای کاربران عادی) -->
                @if(Auth::user()->email != 'admin@example.com')
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-red-600 mb-4">حذف حساب</h3>
                    <p class="text-sm text-gray-600 mb-4">با حذف حساب، تمام اطلاعات شما از سیستم پاک خواهد شد. این عمل قابل بازگشت نیست.</p>
                    <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('آیا از حذف حساب خود اطمینان دارید؟');">
                        @csrf
                        @method('DELETE')
                        <div class="flex items-center gap-4">
                            <input type="password" name="password" placeholder="رمز عبور خود را وارد کنید" 
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                                   required>
                            <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition">
                                حذف حساب
                            </button>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatarPreview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
        
        // آپلود خودکار عکس
        uploadAvatar(input.files[0]);
    }
}

function uploadAvatar(file) {
    var formData = new FormData();
    formData.append('avatar', file);
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('_method', 'PATCH');

    fetch('{{ route("profile.update") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // show success message
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>
@endsection