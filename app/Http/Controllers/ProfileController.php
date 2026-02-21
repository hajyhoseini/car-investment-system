<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Intervention\Image\Laravel\Facades\Image;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:8|confirmed',
        ];

        // اگه فایل آپلود شده
        if ($request->hasFile('avatar')) {
            $rules['avatar'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        $validated = $request->validate($rules);

     // آپلود عکس
if ($request->hasFile('avatar')) {
    // حذف عکس قبلی
    if ($user->avatar) {
        $oldAvatarPath = public_path('uploads/avatars/' . $user->avatar);
        if (file_exists($oldAvatarPath)) {
            unlink($oldAvatarPath);
        }
    }

    // آپلود و ذخیره عکس جدید
    $avatar = $request->file('avatar');
    $filename = time() . '_' . uniqid() . '.' . $avatar->getClientOriginalExtension();
    
    // ذخیره مستقیم در پوشه public/uploads/avatars
    $avatar->move(public_path('uploads/avatars'), $filename);
    
    $user->avatar = $filename;
}

        // بروزرسانی رمز عبور
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->bio = $request->bio;
        $user->save();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'avatar_url' => $user->avatar_url]);
        }

        return Redirect::route('profile.edit')->with('success', 'پروفایل با موفقیت به‌روزرسانی شد.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // حذف عکس پروفایل
        if ($user->avatar) {
            Storage::disk('public')->delete('avatars/' . $user->avatar);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}