<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
class ProfileController extends Controller
{
    // Hiển thị trang hồ sơ
    public function show()
    {
        $user = Auth::user();
        $student = $user->student;

        return view('frontend.profile.view', compact('user', 'student'));
    }

    // Cập nhật thông tin hồ sơ (tên, số điện thoại, địa chỉ,...)
    public function update(Request $request)
{
    $request->validate([
        'full_name' => 'required|string|max:255',
        'phone'     => 'nullable|string|max:20',
        'address'   => 'nullable|string|max:255',
        'description' => 'nullable|string|max:1000',
    ]);

    $user = auth()->user();
    $user->full_name = $request->input('full_name');
    
    // Kiểm tra nếu có giá trị trong trường 'phone' trước khi cập nhật
    $user->phone = $request->input('phone', $user->phone); // Nếu không có giá trị mới thì giữ nguyên giá trị cũ

    $user->address = $request->input('address');
    $user->description = $request->input('description');
    $user->save();

    return back()->with('success', 'Cập nhật hồ sơ thành công.');
}

    // Cập nhật ảnh đại diện
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();

        // Xoá ảnh cũ nếu có
        if ($user->photo && Storage::disk('public')->exists(str_replace('storage/', '', $user->photo))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $user->photo));
        }

        // Lưu ảnh mới
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->photo = 'storage/' . $path;
        $user->save();

        return back()->with('success', 'Ảnh đại diện đã được cập nhật.');
    }

    // Cập nhật mật khẩu
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        return back()->with('success', 'Đổi mật khẩu thành công.');
    }
}
