<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Modules\Teaching_1\Models\Donvi;
use App\Modules\Teaching_1\Models\Nganh;
use App\Modules\Teaching_1\Models\ClassModel;
use App\Modules\Teaching_1\Models\Student;
use App\Modules\Teaching_1\Models\Teacher;

class RoleSelectionController extends Controller
{
    public function showRoleSelection()
    {
        $user = Auth::user();

        if (in_array($user->role, ['student', 'teacher'])) {
            return redirect()->route('home')->with('error', 'Bạn đã chọn vai trò trước đó.');
        }

        return view('frontend.role_selection');
    }

    public function saveRole(Request $request)
{
    $user = Auth::user();
    $role = $request->input('role');

    // Kiểm tra vai trò hợp lệ
    if (!in_array($role, ['student', 'teacher'])) {
        return redirect()->route('chon.vai.tro')->with('error', 'Vai trò không hợp lệ.');
    }

    // Nếu role là teacher, kiểm tra email người dùng có hợp lệ không
    if ($role == 'teacher' && !str_ends_with($user->email, '@dhtn.edu.vn')) {
        return redirect()->route('chon.vai.tro')->with('error', 'Vai trò không hợp lệ.');
    }

    // Lưu vai trò cho người dùng
    $user->role = $role;
    $user->save();

    return redirect()->route('chon.thong.tin');
}


    public function showRoleDetails()
    {
        $user = Auth::user();

        $donviList = Donvi::all();
        $nganhList = Nganh::all();
        $classList = ClassModel::all();

        if ($user->role == 'student') {
            return view('frontend.role_details_student', compact('donviList', 'nganhList', 'classList'));
        }

        if ($user->role == 'teacher') {
            return view('frontend.role_details_teacher', compact('donviList', 'nganhList'));
        }

        return redirect()->route('home')->with('error', 'Không tìm thấy vai trò.');
    }

    public function saveRoleDetails(Request $request)
    {
        $user = Auth::user();

        // Lưu thông tin cho sinh viên
        if ($user->role == 'student') {
            $request->validate([
                'donvi_id' => 'required|exists:donvi,id',
                'nganh_id' => 'required|exists:nganh,id',
                'class_id' => 'required|exists:classes,id',
            ]);

            $student = new Student();
            $student->user_id = $user->id;
            $student->donvi_id = $request->donvi_id;
            $student->nganh_id = $request->nganh_id;
            $student->class_id = $request->class_id;
            $student->khoa = now()->year; // Hoặc lấy từ người dùng nếu cần
            $student->mssv = $this->generateMSSV(); // Hàm tạo MSSV
            $student->status = 'đang học';
            $student->slug = \Str::slug($student->mssv);
            $student->save();
        }

        // Lưu thông tin cho giảng viên
        if ($user->role == 'teacher') {
            $request->validate([
                'mgv' => 'required|unique:teacher,mgv', // Kiểm tra MGV có duy nhất không
                'ma_donvi' => 'required|exists:donvi,id',
                'chuyen_nganh' => 'required|exists:nganh,id',
                'hoc_ham' => 'nullable|string|max:255',
                'hoc_vi' => 'nullable|string|max:255',
                'loai_giangvien' => 'nullable|string|max:255',
            ]);

            // Tạo mới giảng viên
            $teacher = new Teacher();
            $teacher->user_id = $user->id;
            $teacher->mgv = $request->mgv; // Nhận MGV từ người dùng nhập vào
            $teacher->ma_donvi = $request->ma_donvi;
            $teacher->chuyen_nganh = $request->chuyen_nganh;
            $teacher->hoc_ham = $request->hoc_ham;
            $teacher->hoc_vi = $request->hoc_vi;
            $teacher->loai_giangvien = $request->loai_giangvien;
            $teacher->save();
        }

        return redirect()->route('home')->with('success', 'Thông tin của bạn đã được lưu.');
    }

    // Hàm tạo MSSV tự động
    private function generateMSSV()
    {
        $lastStudent = Student::latest()->first();
        $lastId = $lastStudent ? intval(substr($lastStudent->mssv, -4)) : 0;
        $newId = str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
        return 'SV' . now()->format('y') . $newId; // Ví dụ: SV25xxxx
    }
}
