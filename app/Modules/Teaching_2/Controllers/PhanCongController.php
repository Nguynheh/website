<?php

namespace App\Modules\Teaching_2\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Teaching_1\Models\ClassModel;
use Illuminate\Http\Request;
use App\Modules\Teaching_2\Models\PhanCong;
use App\Modules\Teaching_1\Models\Teacher;
use App\Modules\Teaching_2\Models\HocPhan;
use App\Modules\Teaching_2\Models\HocKy;
use App\Modules\Teaching_2\Models\NamHoc;


class PhanCongController extends Controller
{
    public function index()
    {
        $phancongs = PhanCong::with(['giangvien:id,mgv', 'hocphan', 'hocky', 'namhoc', 'class'])->paginate(10);
        $active_menu = 'phancong';

        return view('Teaching_2::phancong.index', compact('phancongs', 'active_menu'));
    }

    public function create()
    {
        $giangviens = Teacher::all(['id', 'mgv']);
        $hocphans = HocPhan::all();
        $hockys = HocKy::all();
        $namhocs = NamHoc::all();
        $lopshp = ClassModel::all(); // Lớp học phần
        $active_menu = 'phancong';

        return view('Teaching_2::phancong.create', compact('giangviens', 'hocphans', 'hockys', 'namhocs', 'lopshp', 'active_menu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'giangvien_id' => 'required|exists:teacher,mgv',
            'class_id' => 'required|exists:classes,id',
            'hocphan_id' => 'required|exists:hoc_phans,id',
            'hocky_id' => 'required|exists:hoc_ky,id',
            'namhoc_id' => 'required|exists:nam_hoc,id',
            'ngayphancong' => 'required|date',
            'time_start' => 'nullable|date',
            'time_end' => 'nullable|date',
        ]);

        $giangvien = Teacher::where('mgv', $request->giangvien_id)->first();

        if (!$giangvien) {
            return redirect()->route('phancong.create')->with('error', 'Mã giảng viên không tồn tại!');
        }

        PhanCong::create([
            'giangvien_id' => $giangvien->id,
            'class_id' => $request->class_id,
            'hocphan_id' => $request->hocphan_id,
            'hocky_id' => $request->hocky_id,
            'namhoc_id' => $request->namhoc_id,
            'ngayphancong' => $request->ngayphancong,
            'time_start' => $request->time_start,
            'time_end' => $request->time_end,
        ]);

        return redirect()->route('phancong.index')->with('success', 'Phân công mới đã được tạo!');
    }

    public function edit($id)
    {
        $phancong = PhanCong::findOrFail($id);
        $giangviens = Teacher::all(['id', 'mgv']);
        $hocphans = HocPhan::all();
        $hockys = HocKy::all();
        $namhocs = NamHoc::all();
        $lopshp = ClassModel::all();
        $active_menu = 'phancong';

        return view('Teaching_2::phancong.edit', compact('phancong', 'giangviens', 'hocphans', 'hockys', 'namhocs', 'lopshp', 'active_menu'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'giangvien_id' => 'required|exists:teacher,mgv',
            'class_id' => 'required|exists:classes,id',
            'hocphan_id' => 'required|exists:hoc_phans,id',
            'hocky_id' => 'required|exists:hoc_ky,id',
            'namhoc_id' => 'required|exists:nam_hoc,id',
            'ngayphancong' => 'required|date',
            'time_start' => 'nullable|date',
            'time_end' => 'nullable|date',
        ]);

        $phancong = PhanCong::findOrFail($id);
        $giangvien = Teacher::where('mgv', $request->giangvien_id)->first();

        if (!$giangvien) {
            return redirect()->route('phancong.edit', ['id' => $id])->with('error', 'Mã giảng viên không tồn tại!');
        }

        $phancong->update([
            'giangvien_id' => $giangvien->id,
            'class_id' => $request->class_id,
            'hocphan_id' => $request->hocphan_id,
            'hocky_id' => $request->hocky_id,
            'namhoc_id' => $request->namhoc_id,
            'ngayphancong' => $request->ngayphancong,
            'time_start' => $request->time_start,
            'time_end' => $request->time_end,
        ]);

        return redirect()->route('phancong.index')->with('success', 'Phân công đã được cập nhật!');
    }

    public function destroy($id)
    {
        $phancong = PhanCong::find($id);
        if (!$phancong) {
            return redirect()->route('phancong.index')->with('error', 'Không tìm thấy phân công!');
        }

        $phancong->delete();

        return redirect()->route('phancong.index')->with('success', 'Phân công đã được xóa!');
    }
}
