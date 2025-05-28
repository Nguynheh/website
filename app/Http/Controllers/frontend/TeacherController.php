<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Teaching_2\Models\HocPhan;
use Illuminate\Support\Facades\Auth;
use App\Modules\Teaching_2\Models\PhanCong;
use App\Modules\Teaching_3\Models\ThoiKhoaBieu;
use App\Modules\Teaching_3\Models\DiaDiem;
use App\Modules\Teaching_3\Models\Attendance;
use App\Modules\Teaching_1\Models\Diemdanh;
use App\Modules\Exercise\Models\Assignment;
use App\Modules\Exercise\Models\BoDeTracNghiem;
use App\Modules\Exercise\Models\BoDeTuLuan;

use App\Modules\Exercise\Models\TracNghiemCauhoi;
use App\Modules\Exercise\Models\Tuluancauhoi;
use App\Modules\Exercise\Models\TracNghiemSubmission;
use App\Modules\Exercise\Models\TuLuanSubmission;
use App\Modules\Resource\Models\Resource;
use App\Modules\Exercise\Models\NoidungPhancong;
use App\Modules\Exercise\Models\TracNghiemDapan;
use Illuminate\Support\Str;
use App\Models\User;
use App\Modules\Teaching_3\Models\LichThi;
use App\Modules\Teaching_1\Models\teacher;
use App\Modules\Teaching_3\Models\Enrollment;
use App\Modules\Teaching_3\Models\EnrollResult;
use App\Models\CustomNotification;
use App\Modules\Teaching_1\Models\Student;

class TeacherController extends Controller
{

    
public function quanLiGiangVien()
{
    $mgv = Auth::user()->teacher->mgv;

    $phancongs = PhanCong::whereHas('giangvien', function ($query) use ($mgv) {
        $query->where('mgv', $mgv);
})->orderByDesc('id')->get();

    if ($phancongs->isEmpty()) {
        return redirect()->back()->with('error', 'Giảng viên chưa có phân công.');
    }

    $phancongId = request()->query('phancong_id');
    $phancongActive = null;

    if ($phancongId && $phancongs->contains('id', $phancongId)) {
        $phancongActive = $phancongs->firstWhere('id', $phancongId);

        $students = $phancongActive->enrollments()->with('user')->get();
        $tkbs = ThoiKhoaBieu::where('phancong_id', $phancongActive->id)->with('diaDiem')->get();

        $attendances = collect();
        $tkb_ids = $tkbs->pluck('id');

        $attendances = Attendance::whereIn('tkb_id', $tkb_ids)
            ->with('thoikhoabieu.diaDiem')
            ->get();

        foreach ($attendances as $attendance) {
            $userList = json_decode($attendance->user_list, true);
            $presentCount = $absentCount = $lateCount = 0;
            foreach ($userList as $user) {
                if ($user['status'] === 'present') $presentCount++;
                elseif ($user['status'] === 'absent') $absentCount++;
                elseif ($user['status'] === 'late') $lateCount++;
            }
            $attendance->present_count = $presentCount;
            $attendance->absent_count = $absentCount;
            $attendance->late_count = $lateCount;
        }

        $noidungPhancong = NoidungPhanCong::where('phancong_id', $phancongActive->id)->first();

        if ($noidungPhancong) {
            $tuluanData = json_decode($noidungPhancong->tuluan ?? '{}', true);
            $tracnghiemData = json_decode($noidungPhancong->tracnghiem ?? '{}', true);
            $resourcesData = json_decode($noidungPhancong->resources ?? '{}', true);

            $boDeTuLuan = BoDeTuLuan::whereIn('id', $tuluanData['bodetuluan_ids'] ?? [])->get();
            $boDeTracNghiem = BoDeTracNghiem::whereIn('id', $tracnghiemData['bodetracnghiem_ids'] ?? [])->get();
            $resources = Resource::whereIn('id', $resourcesData['resource_ids'] ?? [])->get();
        } else {
            $boDeTuLuan = collect();
            $boDeTracNghiem = collect();
            $resources = collect();
        }

    } else {
        // Chưa chọn lớp => các biến còn lại là null hoặc collection rỗng
        $students = collect();
        $tkbs = collect();
        $attendances = collect();
        $noidungPhancong = null;
        $boDeTuLuan = collect();
        $boDeTracNghiem = collect();
        $resources = collect();
    }
    
    return view('frontend.teacher.lophocphan', compact(
        'phancongs',
        'phancongActive',
        'students',
        'tkbs',
        'attendances',
        'noidungPhancong',
        'boDeTuLuan',
        'boDeTracNghiem',
        'resources'
    ));
}


public function addAssignment(Request $request)
{
    // Validate dữ liệu
    $validated = $request->validate([
        'bodetuluan_ids' => 'nullable|array',
        'bodetuluan_ids.*' => 'exists:bode_tuluans,id',
        'bodetracnghiem_ids' => 'nullable|array',
        'bodetracnghiem_ids.*' => 'exists:bo_de_trac_nghiems,id',
        'noidungphancong_id' => 'required|exists:noidung_phancong,id',
    ]);

    $noidungPhancong = NoidungPhanCong::findOrFail($validated['noidungphancong_id']);

    // Xử lý tự luận nếu có
    if (!empty($validated['bodetuluan_ids'])) {
        $existingTuluanData = json_decode($noidungPhancong->tuluan ?? '{}', true);
        $existingTuluanIds = $existingTuluanData['bodetuluan_ids'] ?? [];

        $newTuluanIds = array_unique(array_merge($existingTuluanIds, $validated['bodetuluan_ids']));

        $allCauHoi = collect();
        foreach ($validated['bodetuluan_ids'] as $boDeId) {
            $boDe = BoDeTuLuan::find($boDeId);
            if ($boDe && $boDe->questions) {
                $allCauHoi = $allCauHoi->merge($boDe->tuluanCauhois());
            }
        }

        $cauHoiData = $allCauHoi->map(function ($cauhoi) {
            return [
                'id' => $cauhoi->id,
                'content' => $cauhoi->content,
            ];
        })->values()->all();

        $noidungPhancong->tuluan = json_encode([
            'bodetuluan_ids' => $newTuluanIds,
            'cauhoi' => $cauHoiData,
        ]);
    }

    // Xử lý trắc nghiệm nếu có
    if (!empty($validated['bodetracnghiem_ids'])) {
        $existingTracnghiemData = json_decode($noidungPhancong->tracnghiem ?? '{}', true);
        $existingTracnghiemIds = $existingTracnghiemData['bodetracnghiem_ids'] ?? [];

        $newTracnghiemIds = array_unique(array_merge($existingTracnghiemIds, $validated['bodetracnghiem_ids']));

        $allTracNghiemCauHoi = collect();
        foreach ($validated['bodetracnghiem_ids'] as $boDeId) {
            $boDeTracNghiem = BoDeTracNghiem::find($boDeId);
            if ($boDeTracNghiem && $boDeTracNghiem->questions) {
                $allTracNghiemCauHoi = $allTracNghiemCauHoi->merge($boDeTracNghiem->tracnghiemCauhois());
            }
        }

        $tracNghiemCauHoiData = $allTracNghiemCauHoi->map(function ($cauhoi) {
            return [
                'id' => $cauhoi->id,
                'content' => $cauhoi->content,
            ];
        })->values()->all();

        $noidungPhancong->tracnghiem = json_encode([
            'bodetracnghiem_ids' => $newTracnghiemIds,
            'cauhoi' => $tracNghiemCauHoiData,
        ]);
    }

    // Lưu lại nội dung phân công
    $noidungPhancong->save();

    return redirect()->route('teacher.quanly')->with('success', 'Bài tập đã được thêm thành công.');
}


public function uploadResource(Request $request, $phancong_id)
{
    if ($request->hasFile('documents')) {
        $resource_ids = [];

        // Lưu tài liệu vào database và lấy các resource_ids
        foreach ($request->file('documents') as $file) {
            $extension = $file->getClientOriginalExtension();

            // Gán type_code theo đuôi file
            $type_code = match (strtolower($extension)) {
                'pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx' => 'document',
                'jpg', 'jpeg', 'png' => 'image',
                'mp3' => 'audio',
                'mp4', 'mov' => 'video',
                default => 'other',
            };

            // Lưu file vào thư mục uploads/resources trong storage
            $path = $file->store('uploads/resources', 'public');

            // Tạo URL truy cập tài liệu
$url = asset('storage/' . $path);
            // Lưu thông tin tài liệu vào bảng Resource
            $resource = Resource::create([
                'title' => $file->getClientOriginalName(),
                'slug' => Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . time(),
                'url' => $url,  // Lưu URL đầy đủ vào cột 'url'
                'type_code' => $type_code,
                'phancong_id' => $phancong_id,
            ]);

            // Thêm ID tài liệu vào mảng resource_ids
            $resource_ids[] = $resource->id;
        }

        // Cập nhật cột resources trong NoidungPhanCong
        $noidungPhanCong = NoidungPhanCong::where('phancong_id', $phancong_id)->first();
        if ($noidungPhanCong) {
            // Lưu danh sách resource_ids dưới dạng JSON vào cột resources
            $noidungPhanCong->update([
                'resources' => json_encode([
                    'noidungphancong_id' => $noidungPhanCong->id,
                    'resource_ids' => $resource_ids,
                ]),
            ]);
        }

        return back()->with('success', 'Tải tài liệu thành công.');
    }

    return back()->with('error', 'Vui lòng chọn ít nhất một tài liệu.');
}


public function createTkb()
    {
        $teacher = Auth::user()->teacher; // Lấy thông tin giảng viên đã đăng nhập
        $phancongs = $teacher->phancongs()->with('hocphan')->get(); // Lấy tất cả phân công giảng dạy của giảng viên
        $diadiems = DiaDiem::all(); // Lấy tất cả địa điểm giảng dạy

        return view('frontend.teacher.dangkilich', compact('phancongs', 'diadiems'));
    }

       public function lichgiangday()
{
    $user = Auth::user();
    if (!$user->teacher) {
        abort(403);
    }
    $teacher = $user->teacher;

    $today = \Carbon\Carbon::today();

    $tkbs = ThoiKhoaBieu::with([
            'phancong.hocphan',
            'phancong.hocky',
            'phancong.namhoc',
            'diaDiem'
        ])
        ->whereHas('phancong', function ($query) use ($teacher) {
            $query->where('giangvien_id', $teacher->id);
        })
        ->whereDate('ngay', '>=', $today) // chỉ lấy từ hôm nay trở đi
        ->orderBy('ngay')
        ->get();

    return view('frontend.teacher.lichgiangday', compact('tkbs'));
}


    // Lưu lịch giảng dạy
    public function storeTkb(Request $request)
{
    // 1. Validation dữ liệu
    $validated = $request->validate([
        'phancong_id' => 'required|exists:phancong,id',
        'diadiem_id' => 'required|exists:dia_diem,id',
        'ngay' => 'required|date',
        'buoi' => 'required|string',
        'tietdau' => 'required|integer',
        'tietcuoi' => 'required|integer',
    ]);

    // 2. Kiểm tra lịch giảng dạy có trùng hay không
    $existingTkb = ThoiKhoaBieu::where('phancong_id', $validated['phancong_id'])
        ->where('ngay', $validated['ngay'])
        ->where('buoi', $validated['buoi'])
        ->where(function($query) use ($validated) {
            $query->whereBetween('tietdau', [$validated['tietdau'], $validated['tietcuoi']])
                  ->orWhereBetween('tietcuoi', [$validated['tietdau'], $validated['tietcuoi']])
                  ->orWhere(function($query2) use ($validated) {
                      $query2->where('tietdau', '<', $validated['tietdau'])
                             ->where('tietcuoi', '>', $validated['tietcuoi']);
                  });
        })
        ->exists();

    // Nếu có lịch trùng, trả về thông báo lỗi
    if ($existingTkb) {
        return redirect()->back()->withErrors('Lịch giảng dạy này đã trùng với một lịch khác vào cùng ngày, buổi hoặc thời gian. Vui lòng kiểm tra lại.');
    }

    // 3. Tạo mới thời khóa biểu
    $tkb = ThoiKhoaBieu::create([
        'phancong_id' => $validated['phancong_id'],
        'diadiem_id' => $validated['diadiem_id'],
        'ngay' => $validated['ngay'],
        'buoi' => $validated['buoi'],
        'tietdau' => $validated['tietdau'],
        'tietcuoi' => $validated['tietcuoi'],
    ]);

    // 4. Gửi thông báo tới sinh viên của phân công này
    $phancong = PhanCong::with('hocphan')->find($validated['phancong_id']);
    $userIds = Enrollment::where('phancong_id', $phancong->id)->pluck('user_id');

    foreach ($userIds as $userId) {
        CustomNotification::create([
            'user_id' => $userId,
            'title' => 'Lịch học mới',
            'message' => 'Bạn có lịch học mới cho học phần "' . $phancong->hocphan->title . '" vào ngày ' . $validated['ngay'] . '.',
            'type' => 'Lịch học', // Hoặc giá trị mà bạn muốn gán cho `type`
            'is_read' => false,
        ]);
    }

    // 5. Trả về với thông báo thành công
    return redirect()->route('teacher.tkb.create')->with('success', 'Đăng ký lịch giảng dạy thành công và đã gửi thông báo đến sinh viên.');
}

public function diemDanh(Request $request)
{
    // Lấy thông tin từ form
    $tkbId = $request->input('tkb_id');

    
    // Xử lý điểm danh: Lưu vào bảng Attendance hoặc logic khác.
    // Giả sử bạn tạo một bản ghi mới trong bảng Attendance
    $attendance = new Attendance();
    $attendance->tkb_id = $tkbId;
    $attendance->save();

    // Redirect hoặc trả về view
    return redirect()->route('teacher.diemdanh.form', ['tkb_id' => $tkbId]);
}

// Hiển thị form điểm danh cho 1 buổi học
public function diemDanhForm($tkb_id)
{
    // Lấy thông tin thời khóa biểu (ThoiKhoaBieu) và dữ liệu phân công, nhóm, sinh viên
    $tkb = ThoiKhoaBieu::with([
        'phancong.enrollments.user' // Lấy thông tin sinh viên đã đăng ký học phần
    ])
    ->findOrFail($tkb_id);

    // Kiểm tra xem có dữ liệu điểm danh trước đó hay không
    $attendance = Attendance::where('tkb_id', $tkb_id)->first();

    // Lấy danh sách sinh viên đã đăng ký vào lớp học từ bảng enrollments
    $students = $tkb->phancong->enrollments->pluck('user')->unique('id');

    // Trả về view và truyền dữ liệu
    return view('frontend.teacher.diemdanh', compact('tkb', 'students', 'attendance'));
}


// Lưu điểm danh
public function saveDiemDanh(Request $request, $tkb_id)
{
    $tkb = ThoiKhoaBieu::findOrFail($tkb_id);
    $hocphan_id = $tkb->phancong->hocphan_id;

    $statusMapping = [
        'present' => 1,
        'absent' => 2,
        'late' => 3,
    ];

    $studentMap = Student::pluck('id', 'user_id'); // user_id => student_id

    $presentCount = $absentCount = $lateCount = 0;
    $userList = [];  // Danh sách chi tiết user_id và trạng thái

    // Lặp qua tất cả sinh viên và trạng thái của họ
    foreach ($request->user_list as $userId => $status) {
        $studentId = $studentMap[$userId] ?? null;

        if ($studentId) {
            // Lưu điểm danh vào bảng 'diemdanh'
            Diemdanh::updateOrCreate(
                [
                    'sinhvien_id' => $studentId,
                    'hocphan_id' => $hocphan_id,
                    'time' => $tkb->ngay, // Lấy ngày từ bảng ThoiKhoaBieu
                ],
                [
                    'trangthai' => $statusMapping[$status] ?? 0,
                ]
            );

            // Cập nhật số lượng điểm danh theo trạng thái
            if ($status === 'present') {
                $presentCount++;
            } elseif ($status === 'absent') {
                $absentCount++;
            } elseif ($status === 'late') {
                $lateCount++;
            }

            // Thêm thông tin user vào userList
            $userList[] = [
                'user_id' => $userId,
                'status' => $status,  // trạng thái của sinh viên
                'time' => now()->format('Y-m-d H:i:s')
            ];
        }
    }

    // Cập nhật hoặc tạo mới thông tin tổng hợp điểm danh trong bảng attendance
    Attendance::updateOrCreate(
        ['tkb_id' => $tkb_id],
        [
            'total_students' => count($request->user_list),
            'present_count' => $presentCount,
            'absent_count' => $absentCount,
            'late_count' => $lateCount,
            'user_list' => json_encode($userList),  // Lưu danh sách chi tiết vào user_list
        ]
    );

    return redirect()->back()->with('success', 'Lưu điểm danh thành công.');
}

public function thongKeDiemDanh($phancong_id)
{
    // Lấy phân công để lấy học phần
    $phancong = PhanCong::findOrFail($phancong_id);
    $hocphan_id = $phancong->hocphan_id;

    // Lấy danh sách sinh viên đã đăng ký phân công này
    $enrollments = Enrollment::where('phancong_id', $phancong_id)
        ->with('user')
        ->get();

    // Map user_id => student_id
    $studentMap = Student::pluck('id', 'user_id');

    $results = [];

    foreach ($enrollments as $enrollment) {
        $user = $enrollment->user;
        $studentId = $studentMap[$user->id] ?? null;

        if (!$studentId) continue;

        // Lấy danh sách điểm danh
        $diemdanhRecords = Diemdanh::where('sinhvien_id', $studentId)
            ->where('hocphan_id', $hocphan_id)
            ->get();

        $results[] = [
            'name' => $user->full_name,
            'present' => $diemdanhRecords->where('trangthai', 'có mặt')->count(),
            'absent'  => $diemdanhRecords->where('trangthai', 'vắng mặt')->count(),
            'late'    => $diemdanhRecords->where('trangthai', 'muộn')->count(),
        ];
    }

    return view('frontend.teacher.thongke_diemdanh', compact('results', 'phancong_id', 'hocphan_id'));
}





// public function listSubmissions($assignment_id)
// {
//     // Lấy thông tin bài tập theo assignment_id
//     $assignment = Assignment::findOrFail($assignment_id);

//     // Kiểm tra loại bài tập (trắc nghiệm hay tự luận)
//     if ($assignment->quiz_type == 'trac_nghiem') {
//         // Lấy danh sách bài nộp trắc nghiệm
//         $submissions = TracNghiemSubmission::where('assignment_id', $assignment_id)
//             ->with('student')
//             ->get();
//     } else {
//         // Lấy danh sách bài nộp tự luận
//         $submissions = TuLuanSubmission::where('assignment_id', $assignment_id)
//             ->with('student')
//             ->get();
//     }

//     // Trả về view với biến assignment và submissions
//     return view('frontend.teacher.submissions_list', compact('assignment', 'submissions'));
// }
public function showSubmissionDetail($phancong_id)
{
    // Lấy thông tin phân công
    $phancong = PhanCong::findOrFail($phancong_id);

    // Lấy nội dung phân công
    $noidungPhancong = NoidungPhancong::where('phancong_id', $phancong_id)->firstOrFail();

    // Giải mã JSON
    $tuluanData = json_decode($noidungPhancong->tuluan, true) ?: [];
    $tracnghiemData = json_decode($noidungPhancong->tracnghiem, true) ?: [];
    $resourcesData = json_decode($noidungPhancong->resources, true) ?: [];

    // Lấy danh sách ID, ép kiểu integer và lọc các giá trị không hợp lệ
    $bodetuluanIds = array_filter(array_map('intval', $tuluanData['bodetuluan_ids'] ?? []));
    $bodetracnghiemIds = array_filter(array_map('intval', $tracnghiemData['bodetracnghiem_ids'] ?? []));
    $resourceIds = array_filter(array_map('intval', $resourcesData['resource_ids'] ?? []));

    // Kiểm tra và lấy dữ liệu từ các bảng tương ứng
    $boDeTuLuan = BoDeTuLuan::whereIn('id', $bodetuluanIds)->get();
    $boDeTracNghiem = BoDeTracNghiem::whereIn('id', $bodetracnghiemIds)->get();
    $resources = Resource::whereIn('id', $resourceIds)->get();

    // Kiểm tra nếu không có dữ liệu trả về thông báo hoặc trang lỗi
    if ($boDeTuLuan->isEmpty() && $boDeTracNghiem->isEmpty() && $resources->isEmpty()) {
        return redirect()->back()->withErrors('Không có dữ liệu bộ đề hoặc tài nguyên cho phân công này.');
    }
    // Trả về view
    return view('frontend.teacher.class_details', compact(
        'phancong', 'noidungPhancong', 'boDeTuLuan', 'boDeTracNghiem', 'resources'
    ));
}
public function assignExercise(Request $request, $phancong_id)
{
    $request->validate([
        'bo_de_id' => 'required|integer',
        'loai_bo_de' => 'required|string|in:tu_luan,trac_nghiem',
        'due_date' => 'required|date|after_or_equal:today',
    ]);

    $phancong = PhanCong::with('hocphan')->findOrFail($phancong_id);
    $boDeId = $request->bo_de_id;
    $loaiBoDe = $request->loai_bo_de;

    if ($loaiBoDe === 'tu_luan') {
        $boDe = BoDeTuLuan::find($boDeId);
        if (!$boDe) {
            return back()->with('error', '❌ Bộ đề tự luận không tồn tại!');
        }
    } elseif ($loaiBoDe === 'trac_nghiem') {
        $boDe = BoDeTracNghiem::find($boDeId);
        if (!$boDe) {
            return back()->with('error', '❌ Bộ đề trắc nghiệm không tồn tại!');
        }
    } else {
        return back()->with('error', '❌ Loại bộ đề không hợp lệ!');
    }

    // Kiểm tra xem bộ đề đó đã được giao chưa
    $exists = Assignment::where('hocphan_id', $phancong->hocphan_id)
        ->where('quiz_type', $loaiBoDe)
        ->where('quiz_id', $boDeId)
        ->exists();

    if ($exists) {
        return back()->with('error', "❌ Bộ đề {$loaiBoDe} này đã được giao cho học phần!");
    }

    // Tạo bài tập mới
    $assignment = new Assignment();
    $assignment->hocphan_id = $phancong->hocphan_id;
    $assignment->assigned_at = now()->toDateString();
    $assignment->due_date = date('Y-m-d', strtotime($request->due_date));
    $assignment->quiz_type = $loaiBoDe;
    $assignment->quiz_id = $boDeId;
    $assignment->save();

    // Gửi thông báo đến sinh viên
    $students = Enrollment::where('phancong_id', $phancong_id)->get();
    foreach ($students as $student) {
        CustomNotification::create([
            'user_id' => $student->user_id,
            'title' => 'Bài tập mới',
            'message' => 'Bạn có bài tập mới trong học phần "' . $phancong->hocphan->title . '" với hạn nộp ' . date('d/m/Y', strtotime($assignment->due_date)) . '.',
            'type' => 'Bài tập',
            'is_read' => false,
        ]);
    }

    return back()->with('success', '✅ Giao bài tập thành công và đã gửi thông báo đến sinh viên!');
}




public function editNoiDungPhanCong($id)
{
    $noidungPhancong = NoidungPhanCong::findOrFail($id);

    // Decode JSON ra mảng, đảm bảo là array các int
    $tuluan_ids = json_decode($noidungPhancong->tuluan_ids, true) ?: [];
    $tuluan_ids = is_array($tuluan_ids) ? array_map('intval', $tuluan_ids) : [];

    $tracnghiem_ids = json_decode($noidungPhancong->tracnghiem_ids, true) ?: [];
    $tracnghiem_ids = is_array($tracnghiem_ids) ? array_map('intval', $tracnghiem_ids) : [];

    // Lấy học phần của phân công để lấy bộ đề cùng học phần
    $hocphan_id = $noidungPhancong->phancong->hocphan_id;

    // Bộ đề theo học phần hiện tại
    $bode_tuluans = BoDeTuLuan::where('hocphan_id', $hocphan_id)->get();
    $bode_tracnghiems = BoDeTracNghiem::where('hocphan_id', $hocphan_id)->get();

    // Bộ đề cũ đã được chọn (có thể nằm ngoài học phần hiện tại)
    $old_tuluans = !empty($tuluan_ids)
        ? BoDeTuLuan::whereIn('id', $tuluan_ids)->get()
        : collect();

    $old_tracnghiems = !empty($tracnghiem_ids)
        ? BoDeTracNghiem::whereIn('id', $tracnghiem_ids)->get()
        : collect();

    // Gộp 2 bộ đề (mới + cũ), loại trùng id
    $bode_tuluans = $bode_tuluans->merge($old_tuluans)->unique('id')->values();
    $bode_tracnghiems = $bode_tracnghiems->merge($old_tracnghiems)->unique('id')->values();

    // Lấy tất cả phân công nếu cần (theo bạn)
    $phancongs = PhanCong::all();

    return view('frontend.teacher.edit_noidung', compact(
        'noidungPhancong',
        'phancongs',
        'bode_tuluans',
        'bode_tracnghiems',
        'tuluan_ids',
        'tracnghiem_ids'
    ))->with([
        'breadcrumb' => '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item"><a href="' . route('teacher.edit_noidung', $noidungPhancong->id) . '">Nội dung giảng dạy</a></li>
            <li class="breadcrumb-item active">Chỉnh sửa</li>',
        'active_menu' => 'teaching_content',
        'tag_ids' => DB::table('tag_noidungphancong')->where('noidungphancong_id', $id)->pluck('tag_id')->toArray(),
    ]);
}




public function updateContent(Request $request, $id)
{
    // Validate the request data
    $request->validate([
        'title' => 'required',
        'content' => 'required',
        'time_limit' => 'nullable|numeric',
        'tracnghiem_ids' => 'nullable|array',
        'tracnghiem_ids.*' => 'integer|exists:bode_tracnghiems,id',
         'tuluan_ids' => 'nullable|array',
         'tuluan_ids.*' => 'integer|exists:bode_tuluans,id'
    ]);

    // Find the content to update
    $noidungPhancong = NoidungPhancong::findOrFail($id);

    // Update main content fields
    $noidungPhancong->title = $request->title;
    $noidungPhancong->content = $request->content;
    $noidungPhancong->time_limit = $request->time_limit;

    // ✅ Update tracnghiem field as JSON
    $noidungPhancong->tracnghiem = json_encode([
        'noidungphancong_id' => $noidungPhancong->id,
        'bodetracnghiem_ids' => $request->tracnghiem_ids ?? [],
    ]);
    $noidungPhancong->tuluan = json_encode([
        'noidungphancong_id' => $noidungPhancong->id,
        'bodetuluan_ids' => $request->tuluan_ids ?? [],
    ]);

    
    // Save changes
    $noidungPhancong->save();

    // Handle file uploads if any
    if ($request->hasFile('documents')) {
        // Handle the file upload logic
    }

    return redirect()->route('teacher.edit_noidung', $id)->with('success', 'Nội dung phân công đã được cập nhật!');
}
public function destroyResource($noidungPhancongId, $resourceId)
{
    // Tìm tài nguyên tương ứng
    $resource = Resource::where('noidung_phancong_id', $noidungPhancongId)
                        ->where('id', $resourceId)
                        ->first();

    // Kiểm tra nếu tài nguyên không tồn tại
    if (!$resource) {
        return response()->json(['success' => false, 'message' => 'Tài nguyên không tồn tại.'], 404);
    }

    // Xoá file vật lý nếu tồn tại
    if (file_exists(public_path($resource->url))) {
        try {
            unlink(public_path($resource->url));
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Không thể xóa file.'], 500);
        }
    }

    // Xoá bản ghi trong cơ sở dữ liệu
    $resource->delete();

    return response()->json(['success' => true, 'message' => 'Tài nguyên đã được xóa thành công.']);
}
public function indexQuiz()
{
    $teacherId = auth()->id();

    // Lấy bộ đề trắc nghiệm và gán type là 'tracnghiem'
    $bodeTracNghiem = BoDeTracNghiem::where('user_id', $teacherId)
        ->get()
        ->map(function ($item) {
            $item->type = 'tracnghiem'; // Gán type để view phân biệt
            return $item;
        });

    // Lấy bộ đề tự luận và gán type là 'tuluan'
    $bodeTuLuan = BoDeTuLuan::where('user_id', $teacherId)
        ->get()
        ->map(function ($item) {
            $item->type = 'tuluan'; // Gán type để view phân biệt
            return $item;
        });

    // Gộp cả hai loại bài tập và loại bỏ bộ đề trùng (nếu có)
    $bodes = $bodeTracNghiem->merge($bodeTuLuan)->unique('id')->sortByDesc('created_at'); 

    return view('frontend.teacher.quiz', compact('bodes'));
}


public function showQuiz($id)
{
    $active_menu = 'bode_tracnghiem_show';
$bodeTracNghiem = BoDeTracNghiem::findOrFail($id);

// Giải mã JSON để lấy id_question
$questions = json_decode($bodeTracNghiem->questions, true);

// Lấy tất cả id_question từ JSON
$questionIds = array_column($questions, 'id_question');

// Truy vấn câu hỏi theo các id_question
$cauHois = TracNghiemCauHoi::whereIn('id', $questionIds)->get();

// Lấy tất cả học phần
$hocphan = HocPhan::all();
    $users = User::where('id', $bodeTracNghiem->user_id)->get();
    $tags = \App\Models\Tag::where('status', 'active')->orderBy('title', 'ASC')->get();

    $breadcrumb = '
    <li class="breadcrumb-item"><a href="#">/</a></li>
    <li class="breadcrumb-item active" aria-current="page">Xem chi tiết bộ đề trắc nghiệm</li>';

    // Decode danh sách câu hỏi đã chọn từ JSON
    $selectedQuestions = json_decode($bodeTracNghiem->questions, true) ?? [];

    return view('frontend.teacher.quizshow', compact(
        'bodeTracNghiem',
        'cauHois',
        'hocphan',
        'users',
        'tags',
        'breadcrumb',
        'active_menu',
        'selectedQuestions'
    ));
}



public function createQuiz()
{
    // Lấy danh sách học phần đã được phân công cho giảng viên hiện tại
    $phancongs = Phancong::whereHas('hocphan', function($query) {
        $query->whereIn('id', function ($subQuery) {
            $subQuery->select('hocphan_id')
                ->from('phancong')
                ->whereIn('giangvien_id', function ($subQuery) {
                    $subQuery->select('id')
                        ->from('teacher')
                        ->where('user_id', auth()->id());
                });
        });
    })->get();

    // Lấy danh sách câu hỏi trắc nghiệm chỉ thuộc về học phần mà giảng viên đã phân công
    $questions = TracNghiemCauhoi::with('answers')
        ->whereIn('hocphan_id', $phancongs->pluck('hocphan_id')) // Lọc theo các học phần đã phân công
        ->get();



    // Gửi danh sách loại đề cho view
    $types = [
        'tracnghiem' => 'Trắc nghiệm',
        'tuluan' => 'Tự luận',
    ];

    // Trả về view kèm theo dữ liệu
    return view('frontend.teacher.quizcreate', compact('questions', 'phancongs', 'types'));
}

public function storeQuiz(Request $request)
{
    // Validate dữ liệu đầu vào
    $request->validate([
        'title' => 'required|string|max:255',
        'type' => 'required|in:tracnghiem,tuluan',
        'hocphan_id' => 'required|exists:hoc_phans,id',
        'time' => 'required|integer|min:1',
        'description' => 'nullable|string',
    ]);

    $slug = Str::slug($request->title);
    $userId = auth()->id();
    $hocphanId = $request->hocphan_id;

    // Kiểm tra sự trùng lặp của slug, nếu có thì thêm hậu tố
    $existingSlugCount = BoDeTracNghiem::where('slug', $slug)->count() + BoDeTuLuan::where('slug', $slug)->count();
    if ($existingSlugCount > 0) {
        $slug .= '-' . time(); // Thêm thời gian vào hậu tố để tránh trùng
    }

    // Xử lý với loại câu hỏi trắc nghiệm
    if ($request->type === 'tracnghiem') {
        $request->validate([
            'selected_questions' => 'required|array',
            'points' => 'required|array', // Thêm validation cho mảng scores
        ]);

        $questions = [];
        $totalPoints = 0;

        foreach ($request->input('selected_questions') as $questionId) {
            $points = $request->input("points.$questionId", 0);
            $questions[] = [
                'id_question' => $questionId,
                'points' => $points,
            ];
            $totalPoints += $points;
        }

        BoDeTracNghiem::create([
            'title' => $request->title,
            'slug' => $slug,
            'hocphan_id' => $hocphanId,
            'description' => $request->description,
            'created_by' => $userId,
            'questions' => json_encode($questions),
            'time' => $request->time,
            'user_id' => $userId,
            'total_points' => $totalPoints,
        ]);
    }

    // Xử lý với loại câu hỏi tự luận
    if ($request->type === 'tuluan') {
        $request->validate([
    'essay_questions' => 'required|array|min:1',
    'essay_questions.*' => 'required|string|max:1000',
    'essay_points' => 'required|array|min:1',
    'essay_points.*' => 'required|numeric|min:0',
], [
    'essay_questions.required' => 'Phải nhập ít nhất một câu hỏi tự luận.',
    'essay_questions.*.required' => 'Không được để trống nội dung câu hỏi tự luận.',
    'essay_points.required' => 'Phải nhập điểm cho câu hỏi.',
    'essay_points.*.required' => 'Không được để trống điểm cho câu hỏi.',
    'essay_points.*.numeric' => 'Điểm phải là số.',
    'essay_points.*.min' => 'Điểm phải lớn hơn hoặc bằng 0.',
]);
        $essayList = [];
        $totalPoints = 0;

        foreach ($request->essay_questions as $index => $content) {
            // Lưu câu hỏi vào bảng tu_luan_cauhois
            $essayQuestion = TuLuanCauHoi::create([
                'content' => $content,
                'hocphan_id' => $hocphanId,
                'user_id' => $userId,
                'tags' => $request->input("tags.$index", ''), // Optional field for tags
                'resources' => json_encode($request->input("resources.$index", [])), // Optional field for resources
            ]);

            $points = $request->essay_points[$index] ?? 0;
            $essayList[] = [
                'id_question' => $essayQuestion->id, // Save the question ID for reference
                'points' => $points,
            ];
            $totalPoints += $points;
        }

        // Lưu bộ đề tự luận vào bảng BoDeTuLuan
        BoDeTuLuan::create([
            'title' => $request->title,
            'slug' => $slug,
            'hocphan_id' => $hocphanId,
            'description' => $request->description,
            'created_by' => $userId,
            'questions' => json_encode($essayList),
            'time' => $request->time,
            'user_id' => $userId,
            'total_points' => $totalPoints,
        ]);
    }

   
    

    // Trả về trang tạo quiz với thông báo thành công
    return redirect()->route('teacher.quiz.create')->with('success', 'Tạo bộ đề thành công!');
}

public function destroyQuiz($id)
    {
        // Tìm bộ đề trắc nghiệm hoặc tự luận
        $bodeTracNghiem = BoDeTracNghiem::find($id);
        $bodeTuLuan = BoDeTuLuan::find($id);

        if ($bodeTracNghiem) {
            // Xóa bộ đề trắc nghiệm
            $bodeTracNghiem->delete();
        } elseif ($bodeTuLuan) {
            // Xóa bộ đề tự luận
            $bodeTuLuan->delete();
        } else {
            return redirect()->route('teacher.quiz')->with('error', 'Bộ đề không tồn tại.');
        }

        return redirect()->route('teacher.quiz')->with('success', 'Đã xóa bộ đề thành công.');
    }
public function storeQuestion(Request $request)
{
    // Validate dữ liệu
    $request->validate([
        'new_question' => 'required|string|max:255',
        'new_answer1' => 'required|string|max:255',
        'new_answer2' => 'required|string|max:255',
        'new_answer3' => 'required|string|max:255',
        'new_answer4' => 'required|string|max:255',
        'new_correct_answer' => 'required|in:1,2,3,4',
        'hocphan_id' => 'required|exists:hoc_phans,id',
    ]);

    // Lưu câu hỏi vào bảng trac_nghiem_cauhois với user_id
    $question = new TracNghiemCauhoi();
    $question->content = $request->new_question;
    $question->hocphan_id = $request->hocphan_id;
    $question->user_id = auth()->id(); // Lấy user_id từ người dùng đã đăng nhập
    $question->save();

    // Lưu các đáp án vào bảng trac_nghiem_dapans
    $answers = [
        $request->new_answer1,
        $request->new_answer2,
        $request->new_answer3,
        $request->new_answer4,
    ];

    foreach ($answers as $key => $answer) {
        $dapan = new TracNghiemDapan();
        $dapan->content = $answer;
        $dapan->is_correct = ($key + 1) == $request->new_correct_answer ? 1 : 0;
        $dapan->tracnghiem_id = $question->id;
        $dapan->save();
    }

    // Trả về thông báo thành công
    return redirect()->back()->with('success', 'Câu hỏi và đáp án đã được thêm thành công!');
}
public function examSchedulePage()
{
    $teacherId = auth()->user()->teacher->id;

    $phancongs = PhanCong::where('giangvien_id', $teacherId)->with(['hocphan', 'hocky', 'namhoc'])->get();
    $lichthis = LichThi::whereIn('phancong_id', $phancongs->pluck('id'))->with(['phancong.hocphan', 'diadiem'])->get();
    $diadiems = DiaDiem::all();

    return view('frontend.teacher.examschedule', compact('phancongs', 'lichthis', 'diadiems'));
}
public function createExamSchedule()
{
    $userId = auth()->id();

    // Lấy giảng viên tương ứng từ bảng teachers
    $teacher = Teacher::where('user_id', $userId)->first();

    if (!$teacher) {
        return abort(403, 'Không tìm thấy thông tin giảng viên');
    }

    $phancongs = PhanCong::where('giangvien_id', $teacher->id)
        ->with(['hocphan', 'hocky', 'namhoc'])
        ->get();

    $diadiems = DiaDiem::all();

    return view('frontend.teacher.examschedule', compact('phancongs', 'diadiems'));
}

// Xử lý lưu lịch thi
public function storeExamSchedule(Request $request)
{
    $request->validate([
        'phancong_id' => 'required|exists:phancong,id',
        'buoi' => 'required',
        'ngay1' => 'required|date',
        'ngay2' => 'nullable|date',
        'dia_diem_thi' => 'required|exists:dia_diem,id',
    ]);

    // Lưu lịch thi vào bảng LichThi
    $lichThi = LichThi::create([
        'phancong_id' => $request->phancong_id,
        'buoi' => $request->buoi,
        'ngay1' => $request->ngay1,
        'ngay2' => $request->ngay2,
        'dia_diem_thi' => $request->dia_diem_thi,
    ]);

    // Lấy thông tin học phần từ phân công
    $hocphanId = $lichThi->phancong->hocphan_id;
    $classIds = PhanCong::where('hocphan_id', $hocphanId)->pluck('class_id'); // Lấy tất cả lớp học tương ứng với học phần

    // Lấy danh sách sinh viên từ bảng Enrollment dựa trên class_id
$userIds = DB::table('enrollments')
    ->join('phancong', 'enrollments.phancong_id', '=', 'phancong.id')
    ->whereIn('phancong.class_id', $classIds)
    ->pluck('enrollments.user_id');

    // Gửi thông báo tới từng sinh viên
    foreach ($userIds as $userId) {
        CustomNotification::create([
            'user_id' => $userId,
            'title' => 'Lịch thi mới',
            'message' => 'Có lịch thi mới cho học phần "' . HocPhan::find($hocphanId)->title . '" vào ngày ' . $request->ngay1,
            'type' => 'Lịch thi',
            'is_read' => false,
        ]);
    }

    return redirect()->route('teacher.exam_schedule.index')->with('success', 'Đăng ký lịch thi thành công!');
}

public function nhapDiemIndex()
{
    $user = Auth::user();
    if (!$user->teacher) abort(403);

    $phancongs = PhanCong::with(['hocphan'])
        ->where('giangvien_id', $user->teacher->id)
        ->get();

    return view('frontend.teacher.nhapdiem_index', compact('phancongs'));
}
public function showNhapDiemForm($phancong_id)
{
    $phancong = PhanCong::with('enrollments.user')->findOrFail($phancong_id);

    return view('frontend.teacher.nhapdiem', compact('phancong'));
}

public function saveNhapDiem(Request $request, $phancong_id)
{
    $request->validate([
        'students' => 'required|array',
        'students.*.student_id' => 'required|integer',
        'students.*.DiemBP' => 'nullable|numeric',
        'students.*.Thi1' => 'nullable|numeric',
        'students.*.Thi2' => 'nullable|numeric',
    ]);

    $phancong = PhanCong::with('hocphan')->findOrFail($phancong_id);

    foreach ($request->students as $enroll_id => $data) {
        $student_id = $data['student_id'];
        $diemBP = floatval($data['DiemBP'] ?? 0);
        $thi1 = floatval($data['Thi1'] ?? 0);
        $thi2 = floatval($data['Thi2'] ?? 0);

        $diem1 = $diemBP * 0.4 + $thi1 * 0.6;
        $diem2 = $diemBP * 0.4 + $thi2 * 0.6;
        $diemMax = max($diem1, $diem2);

        // Tính điểm chữ và hệ số
        if ($diemMax >= 8.5) {
            $diemChu = 'A';
            $diemHeSo4 = 4;
        } elseif ($diemMax >= 7.0) {
            $diemChu = 'B';
            $diemHeSo4 = 3;
        } elseif ($diemMax >= 5.5) {
            $diemChu = 'C';
            $diemHeSo4 = 2;
        } elseif ($diemMax >= 4.0) {
            $diemChu = 'D';
            $diemHeSo4 = 1;
        } else {
            $diemChu = 'F';
            $diemHeSo4 = 0;
        }

        // Lưu điểm
        EnrollResult::updateOrCreate(
            [
                'enroll_id' => $enroll_id,
                'student_id' => $student_id,
            ],
            [
                'DiemBP' => $diemBP,
                'Thi1' => $thi1,
                'Thi2' => $thi2,
                'Diem1' => $diem1,
                'Diem2' => $diem2,
                'DiemMax' => $diemMax,
                'DiemChu' => $diemChu,
                'DiemHeSo4' => $diemHeSo4,
            ]
        );

        // Cập nhật trạng thái enrollment thành 'finished'
        // và kết quả (passed hoặc failed)
        $enrollment = Enrollment::find($enroll_id);
if ($enrollment) {
    if ($diemChu === 'F') {
        $enrollment->status = 'rejected'; // rớt
    } else {
        $enrollment->status = 'finished'; // hoàn thành
    }
    $enrollment->save();

}
        if ($enrollment) {
            $userId = $enrollment->user_id;

            CustomNotification::create([
                'user_id' => $userId,
                'title' => 'Cập nhật điểm',
                'message' => 'Điểm của bạn đã được cập nhật cho học phần "' . $phancong->hocphan->title . '". Điểm tối đa là: ' . round($diemMax, 1) . '.',
                'type' => 'Điểm',
                'is_read' => false,
            ]);
        }
    }

    return back()->with('success', 'Lưu điểm và gửi thông báo thành công.');
}



public function showDiem($phancong_id)
{
    $phancong = PhanCong::with(['hocphan', 'enrollments.enrollResult'])->find($phancong_id);

    if (!$phancong) {
        return redirect()->route('teacher.nhapdiem')->with('error', 'Không tìm thấy phân công.');
    }

    return view('frontend.teacher.showDiem', compact('phancong'));
}
public function showThongKe($phancong_id)
{
    // Lấy phân công học phần theo giảng viên
    $phancong = PhanCong::with(['hocphan', 'enrollments.user', 'assignments'])->findOrFail($phancong_id);


    // Lấy tổng số bài tập đã nộp của sinh viên trong học phần này
    $completedAssignments = $phancong->assignments()->whereHas('submissions', function ($query) {
        $query->where('status', 'submitted');
    })->count();

    // Tính điểm trung bình của sinh viên trong học phần
    $averageGrade = $phancong->enrollments->avg(function ($enrollment) {
        return $enrollment->enrollResult ? $enrollment->enrollResult->DiemMax : 0;
    });

    // Trả về view với dữ liệu thống kê
    return view('frontend.teacher.thongke', compact('phancong', 'completedAssignments', 'averageGrade'));
}
public function viewAssignments(Request $request)
{
    $hocphan_id = $request->query('hocphan_id'); // hoặc $request->hocphan_id

    $assignments = Assignment::with(['quizTracNghiem', 'quizTuLuan'])
                         ->when($hocphan_id, function ($query, $hocphan_id) {
                             return $query->where('hocphan_id', $hocphan_id);
                         })
                         ->get();

    return view('frontend.teacher.assignments_index', compact('assignments'));
}
public function viewAssignmentResults($assignmentId)
{
    $assignment = Assignment::findOrFail($assignmentId);
    
    // Kiểm tra xem bài tập có phải là trắc nghiệm hay tự luận
    if ($assignment->quiz_type == 'trac_nghiem') {
        // Nếu là trắc nghiệm, lấy tất cả kết quả trắc nghiệm
        $results = TracNghiemSubmission::where('assignment_id', $assignmentId)->get();
    } else {
        // Nếu là tự luận, lấy tất cả kết quả bài tự luận
        $results = TuluanSubmission::where('assignment_id', $assignmentId)->get();
    }

    return view('frontend.teacher.assignments_results', compact('assignment', 'results'));
}
    public function viewStudentSubmission($assignmentId, $submissionId)
    {
        $assignment = Assignment::findOrFail($assignmentId);

        // Kiểm tra loại bài tập
        if ($assignment->quiz_type == 'trac_nghiem') {
            // Lấy thông tin bài làm trắc nghiệm của sinh viên
            $submission = TracNghiemSubmission::with('student.user')->findOrFail($submissionId);
        
            // Giải mã dữ liệu câu trả lời từ JSON
            $answers = json_decode($submission->answers, true);
        
            // Kiểm tra loại bài tập và lấy quiz tương ứng
            $quiz = $assignment->quiz_type == 'trac_nghiem' 
                ? $assignment->quizTracNghiem
                : $assignment->quizTuLuan;
        
            // Lấy danh sách câu hỏi từ bài kiểm tra (quiz)
            $questionsData = json_decode($quiz->questions ?? '[]', true);  // Giải mã câu hỏi
        
            
        
            // Lấy danh sách câu hỏi từ bảng TracNghiemCauhoi dựa trên ID câu hỏi từ $questionsData
            $questions = TracNghiemCauhoi::whereIn('id', collect($questionsData)->pluck('id_question'))->get()->keyBy('id');
        
            // Truyền dữ liệu vào view
            return view( );
        } else {
            $submission = TuluanSubmission::findOrFail($submissionId);

            // Giải mã câu trả lời JSON thành mảng
            $answers = json_decode($submission->answers, true) ?? [];
        
            // Lấy tất cả ID câu hỏi từ các câu trả lời
            $questionIds = array_keys($answers);
        
            // Lấy thông tin các câu hỏi từ CSDL
            $questions = TuluanCauhoi::whereIn('id', $questionIds)->get()->keyBy('id');
        
            // Trả về view
            return view('frontend.teacher.view_submission', [
                'assignment' => $assignment,
                'submission' => $submission,
                'answers' => $answers,
                'questions' => $questions
            ]);
        
        }
    }

    public function gradeTuluanSubmission(Request $request, $assignmentId, $submissionId)
    {
        $request->validate([
            'score' => 'required|numeric|min:0|max:100',
        ]);

        $submission = TuluanSubmission::findOrFail($submissionId);
        $submission->score = $request->input('score');
        $submission->save();

        return redirect()->back()->with('message', 'Đã chấm điểm thành công!');
    }
}

