@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">
                        مدیریت تصاویر: {{ $car->title }}
                    </h2>
                    <div class="flex gap-2">
                        <a href="{{ route('cars.show', $car) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            بازگشت به خودرو
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- فرم آپلود --}}
                <div class="mb-8 p-6 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4">آپلود تصاویر جدید</h3>
                    <form action="{{ route('cars.images.store', $car) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <input type="file" name="images[]" multiple accept="image/*" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                            @error('images.*') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                            آپلود تصاویر
                        </button>
                    </form>
                </div>

                {{-- لیست تصاویر --}}
                @if($images->count() > 0)
                    <h3 class="text-lg font-semibold mb-4">تصاویر فعلی</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($images as $image)
                            <div class="relative group border rounded-lg overflow-hidden {{ $image->is_primary ? 'ring-4 ring-yellow-400' : '' }}">
                                <img src="{{ $image->url }}" class="w-full h-40 object-cover">
                                
                                @if($image->is_primary)
                                    <div class="absolute top-2 left-2 bg-yellow-500 text-white px-2 py-1 rounded text-xs">
                                        اصلی
                                    </div>
                                @endif

                                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-2">
                                    @if(!$image->is_primary)
                                        <form action="{{ route('cars.images.primary', [$car, $image]) }}" method="POST">
                                            @csrf
                                            <button class="bg-green-500 text-white p-2 rounded-full hover:bg-green-600" title="تنظیم به عنوان اصلی">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3l14 9-14 9V3z"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('cars.images.destroy', [$car, $image]) }}" method="POST" onsubmit="return confirm('آیا از حذف این تصویر اطمینان دارید؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="bg-red-500 text-white p-2 rounded-full hover:bg-red-600" title="حذف">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-gray-500 py-8">هنوز تصویری آپلود نشده است.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection