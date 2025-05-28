<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Modules\Teaching_2\Models\ChuongTrinhDaoTao;
use App\Modules\Teaching_2\Models\HocPhan;
use Illuminate\Http\Request;
use App\Modules\Teaching_2\Models\PhanCong;
use Illuminate\Support\Facades\Auth;
use App\Modules\Teaching_3\Models\ThoiKhoaBieu;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Modules\Exercise\Models\TracNghiemCauhoi;
use App\Modules\Exercise\Models\TracNghiemDapan;
use App\Modules\Exercise\Models\TracNghiemSubmission;
use App\Modules\Exercise\Models\BoDeTracNghiem;
use App\Modules\Exercise\Models\BoDeTuLuan;
use App\Modules\Exercise\Models\Assignment;
use App\Modules\Exercise\Models\TuLuanSubmission;
use App\Modules\Teaching_3\Models\LichThi;
use App\Modules\Exercise\Models\Tuluancauhoi;
use App\Modules\Teaching_3\Models\Enrollment;
use App\Modules\Teaching_2\Models\ProgramDetails;
use App\Modules\Teaching_3\Models\Attendance;
class HocTapController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $student = $user->student;
        if (!$student) {
    return redirect()->back()->with('error', 'Không tìm thấy thông tin sinh viên.');
}
        $nganh_id = $student->nganh_id;

        $chuongTrinh = ChuongTrinhDaoTao::with(['programDetails.hocPhan', 'programDetails.hocKy'])
            ->where('nganh_id', $nganh_id)
            ->first();

        if (!$chuongTrinh) {
            return redirect()->back()->with('error', 'Không tìm thấy chương trình đào tạo cho ngành này.');
        }

        $groupedDetails = $chuongTrinh->programDetails->groupBy(function ($detail) {
            return $detail->hocKy->so_hoc_ky ?? 'Không xác định';
        });

        $hocPhanList = HocPhan::pluck('title', 'id')->toArray();

        return view('frontend.hoctap.programdetails', compact(
            'chuongTrinh',
            'groupedDetails',
            'hocPhanList' 
        ));
    }
    
    

    public function dangKyHocPhan(Request $request)
{
    $request->validate([
        'hoc_phan_id' => 'required|exists:hoc_phans,id',
    ]);

    $user = auth()->user();
    $hocPhan = HocPhan::findOrFail($request->hoc_phan_id);

    // Kiểm tra học phần tiên quyết
    $tienQuyet = json_decode($hocPhan->hocphantienquyet, true);
    $tienQuyetIds = is_array($tienQuyet['next'] ?? null) ? $tienQuyet['next'] : [];

    foreach ($tienQuyetIds as $id) {
        $daHoc = $user->registeredCourses()->whereHas('phancong', function ($q) use ($id) {
            $q->where('hocphan_id', $id);
        })->exists();

        if (!$daHoc) {
            return back()->with('error', 'Bạn cần hoàn thành học phần tiên quyết trước khi đăng ký.');
        }
    }

    // Tìm lớp học phần đầu tiên được mở cho học phần này
    $phancong = PhanCong::where('hocphan_id', $hocPhan->id)->first();
    if (!$phancong) {
        return back()->with('error', 'Hiện tại chưa có lớp học phần nào được mở cho học phần này.');
    }

    // Kiểm tra đã đăng ký lớp này chưa
    $exists = Enrollment::where('user_id', $user->id)
                        ->where('phancong_id', $phancong->id)
                        ->exists();
    if ($exists) {
        return back()->with('error', 'Bạn đã đăng ký lớp học phần này rồi!');
    }

    // Đăng ký lớp chính
    Enrollment::create([
        'user_id' => $user->id,
        'phancong_id' => $phancong->id,
        'timespending' => 0,
        'process' => 0,
        'status' => 'success',
    ]);

    // Đăng ký học phần song song nếu có
    $songSong = json_decode($hocPhan->hocphansongsong, true);
    $songSongIds = is_array($songSong['parallel'] ?? null) ? $songSong['parallel'] : [];

    foreach ($songSongIds as $songSongId) {
        $daCo = $user->registeredCourses()->whereHas('phancong', function ($q) use ($songSongId) {
            $q->where('hocphan_id', $songSongId);
        })->exists();

        if (!$daCo) {
            $phancongSS = PhanCong::where('hocphan_id', $songSongId)->first();
            if ($phancongSS) {
                Enrollment::create([
                    'user_id' => $user->id,
                    'phancong_id' => $phancongSS->id,
                    'timespending' => 0,
                    'process' => 0,
                    'status' => 'success',
                ]);
            }
        }
    }

    return redirect()->route('chuongtrinh.index')->with('success', 'Đăng ký học phần và lớp thành công!');
}


public function registered_courses()
{
    $userId = Auth::id();

    // Lấy tất cả enrollment của sinh viên kèm học phần, kết quả học tập
    $enrollments = Enrollment::with(['phancong.hocphan', 'enrollResult'])
    ->where('user_id', $userId)
    ->get()
    ->filter(function ($e) {
        return $e->phancong && $e->phancong->hocphan;
    });
    return view('frontend.hoctap.registered_courses', compact('enrollments'));
    
}
public function huyDangKy($id)
{
    $userId = Auth::id();

    // Tìm phân công nào thuộc học phần $id mà sinh viên đã đăng ký
    $phancong = Enrollment::where('user_id', $userId)
        ->whereHas('phancong', function ($query) use ($id) {
            $query->where('hocphan_id', $id);
        })
        ->first();

    if ($phancong) {
        $phancong->delete();

        return redirect()->route('frontend.hoctap.registered_courses')->with('success', 'Hủy đăng ký lớp thành công!');
    } else {
        return redirect()->route('frontend.hoctap.registered_courses')->with('error', 'Không tìm thấy lớp đã đăng ký cho học phần này.');
    }
}


        public function chiTietHocPhan($id)
{
    // Lấy phân công tương ứng với học phần
    $phancongIds = PhanCong::where('hocphan_id', $id)->pluck('id');

    // Lấy nội dung phân công
    $noidungs = \App\Modules\Exercise\Models\NoidungPhancong::whereIn('phancong_id', $phancongIds)->get();

    return view('frontend.hoctap.chitiethocphan', compact('noidungs'));
}


public function viewThoiKhoaBieuTheoTuan(Request $request)
{
    $user = auth()->user();

    // Lấy ngày bắt đầu tuần từ URL (nếu có), ngược lại lấy tuần hiện tại
    $startOfWeek = $request->has('start')
        ? Carbon::parse($request->input('start'))->startOfWeek(Carbon::MONDAY)
        : Carbon::now()->startOfWeek(Carbon::MONDAY);

    $endOfWeek = (clone $startOfWeek)->endOfWeek(Carbon::SUNDAY);

    // Lấy thời khóa biểu trong tuần
    $tkbTrongTuan = ThoiKhoaBieu::with([
        'phancong.hocPhan',
        'phancong.giangVien',
        'diaDiem'
    ])
    ->whereBetween('ngay', [$startOfWeek, $endOfWeek])
    ->whereHas('phancong', function ($query) use ($user) {
        $query->whereHas('enrollments', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });
    })
    ->get();

    return view('frontend.hoctap.thoikhoabieu_tuan', compact('tkbTrongTuan', 'startOfWeek', 'endOfWeek'));
}
public function showAssignments(Request $request, $phancong_id)
{
    $user_id = auth()->id();

    // Kiểm tra quyền user với phân công
    $enrollmentExists = Enrollment::where('user_id', $user_id)
                                  ->where('phancong_id', $phancong_id)
                                  ->exists();

    if (!$enrollmentExists) {
        abort(403, 'Bạn không có quyền truy cập phân công này.');
    }

    // Lấy hocphan_id từ phancong
    $hocphan_id = PhanCong::where('id', $phancong_id)->value('hocphan_id');

    // Lấy bài tập của học phần đó
    $assignments = Assignment::where('hocphan_id', $hocphan_id)->get();

    return view('frontend.hoctap.assignments', compact('assignments'));
}






    public function showQuiz($quizId, $assignmentId)
{
    // Lấy bộ đề trắc nghiệm
    $quiz = BodeTracNghiem::findOrFail($quizId);

    // Giải mã trường 'questions' thành mảng
    $questionsData = json_decode($quiz->questions, true);

    // Kiểm tra xem có dữ liệu câu hỏi trong $questionsData không
    if (empty($questionsData)) {
        return view('frontend.hoctap.quiz', compact('quiz', 'assignmentId'))->with('message', 'Không có câu hỏi trong bộ đề');
    }

    // Lấy tất cả câu hỏi từ bảng TracNghiemCauhoi dựa trên các ID trong 'id_question'
    $questions = TracNghiemCauhoi::with('answers')
                                  ->whereIn('id', array_column($questionsData, 'id_question'))  // Dùng 'id_question'
                                  ->get();

    // Kiểm tra nếu không có câu hỏi
    if ($questions->isEmpty()) {
        return view('frontend.hoctap.quiz', compact('quiz', 'assignmentId'))->with('message', 'Không có câu hỏi');
    }

    // Trả về view với quiz, câu hỏi và assignmentId
    return view('frontend.hoctap.quiz', compact('quiz', 'questions', 'assignmentId'));
}

public function submitQuiz(Request $request, $quizId, $assignmentId)
{
    $quiz = BoDeTracNghiem::findOrFail($quizId);
    $answers = $request->input('answers', []);

    // Kiểm tra nếu không có đáp án nào
    if (empty($answers)) {
        return redirect()->back()->with('message', 'Bạn chưa chọn đáp án cho câu hỏi nào!');
    }

    $score = 0;
    $userAnswers = [];

    // Giải mã dữ liệu câu hỏi và điểm
    $questionsData = json_decode($quiz->questions, true);
    // Đặt điểm vào một collection theo id_question
    $pointsMap = collect($questionsData)->keyBy('id_question');

    foreach ($answers as $questionId => $answerId) {
        // Tìm câu hỏi dựa trên questionId
        $question = TracNghiemCauhoi::find($questionId);
        if (!$question) continue;

        // Kiểm tra xem đáp án có đúng không
        $isCorrect = TracNghiemDapan::where([
            ['tracnghiem_id', $question->id],
            ['id', $answerId],
            ['is_correct', 1],
        ])->exists();

        // Nếu đúng và có điểm được định nghĩa thì cộng điểm
        if ($isCorrect && $pointsMap->has((string) $questionId)) {
            $score += (int) $pointsMap->get((string) $questionId)['points'];  // Lấy điểm từ pointsMap
        }

        // Lưu câu trả lời và trạng thái đúng/sai
        $userAnswers[] = [
            'question_id' => $questionId,
            'answer_id' => $answerId,
            'is_correct' => $isCorrect,
        ];
    }

    // Lấy id sinh viên từ thông tin người dùng hiện tại
    $studentId = auth()->user()->student->id;

    // Lưu kết quả bài thi vào bảng TracNghiemSubmission
    TracNghiemSubmission::create([
        'student_id'    => $studentId,
        'quiz_id'       => $quizId,
        'assignment_id' => $assignmentId,
        'answers'       => json_encode($userAnswers),
        'score'         => $score,
        'submitted_at'  => now(),
    ]);

    // Chuyển hướng và thông báo kết quả
    return redirect()
        ->route('frontend.hoctap.quiz.result', ['quizId' => $quizId])
        ->with('message', 'Bạn đã nộp bài thành công. Điểm số của bạn là: ' . $score);
}

public function showQuizResult($quizId)
    {
        // Lấy thông tin quiz từ ID
        $quiz = BodeTracNghiem::findOrFail($quizId);

        // Bạn có thể thêm logic để lấy kết quả đã tính toán và lưu trữ trong cơ sở dữ liệu
        // Ví dụ: Lấy điểm số của người dùng từ bảng `quiz_results` hoặc `user_quiz_scores` nếu có

        // Ở đây chúng ta chỉ trả về một thông báo đơn giản với điểm số (giả định)
        $score = session('score', 0); // Lấy điểm số từ session sau khi người dùng nộp bài

        return view('frontend.hoctap.result', compact('quiz', 'score'));
    }

        public function doTuLuan($assignmentId)
{
    // Lấy bài tập từ bảng assignments
    $assignment = Assignment::findOrFail($assignmentId);

    // Kiểm tra nếu là bài tự luận (quiz_type = 'tu_luan')
    if ($assignment->quiz_type === 'tu_luan') {
        // Lấy bộ đề tự luận từ quiz_id
        $quiz = $assignment->quizTuLuan;  // Lấy bộ đề tự luận từ quan hệ

        // Kiểm tra nếu bộ đề tự luận tồn tại
        if (!$quiz) {
            return redirect()->route('frontend.hoctap.assignments')->with('error', 'Bộ đề tự luận không tồn tại.');
        }

        // Giải mã chuỗi JSON trong trường 'questions' để lấy mảng các câu hỏi
        $questions = json_decode($quiz->questions, true);  // Chuyển chuỗi JSON thành mảng

        // Lấy danh sách các câu hỏi từ bảng TuLuanCauHoi theo id_question
        $cauHoiList = TuLuanCauHoi::whereIn('id', collect($questions)->pluck('id_question'))->get();

        // Kiểm tra nếu không có câu hỏi
        if ($cauHoiList->isEmpty()) {
            return redirect()->route('frontend.hoctap.assignments')->with('error', 'Không có câu hỏi trong bộ đề tự luận.');
        }
    } else {
        // Nếu không phải bài tự luận
        return redirect()->route('frontend.hoctap.assignments')->with('error', 'Bài tập không phải loại tự luận.');
    }

    // Trả về view cùng dữ liệu câu hỏi
    return view('frontend.hoctap.tuluan', compact('assignment', 'cauHoiList'));
}


public function submitTuLuan(Request $request, $assignmentId)
{
    // Validate dữ liệu từ form
    $request->validate([
        'answers' => 'required|array',  // Đảm bảo là mảng
        'answers.*' => 'required|string',  // Đảm bảo mỗi câu trả lời là chuỗi
    ]);

    // Tìm bài tập theo assignmentId
    $assignment = Assignment::find($assignmentId);

    if (!$assignment) {
        return back()->with('error', 'Bài tập không tồn tại.');
    }

    // Kiểm tra xem bài tập có phải là tự luận không
    if ($assignment->quiz_type !== 'tu_luan') {
        return back()->with('error', 'Đây không phải là bài tập tự luận.');
    }

    // Lấy quiz_id từ bộ đề tự luận
    $quizId = $assignment->quiz_id;  // Lấy quiz_id từ bài tập
    $studentId = auth()->user()->student->id;  // Lấy ID sinh viên từ auth

    // Chuyển mảng câu trả lời thành chuỗi JSON
    $answersJson = json_encode($request->input('answers'));

    // Lưu bài làm tự luận vào bảng tu_luan_submissions
    TuLuanSubmission::create([
        'student_id' => $studentId,         // ID sinh viên từ auth
        'assignment_id' => $assignmentId,    // ID bài tập
        'quiz_id' => $quizId,                // ID bộ đề (quiz)
        'answers' => $answersJson,           // Câu trả lời dưới dạng JSON
        'submitted_at' => now(),             // Thời gian nộp bài
    ]);

    // Thông báo thành công
    $assignment = Assignment::find($assignmentId); // hoặc truyền từ request

$hocphan_id = $assignment->hocphan_id;

// Tìm phân công tương ứng (nếu cần)
$phancong_id = PhanCong::where('hocphan_id', $hocphan_id)->first()?->id;

return redirect()->route('frontend.hoctap.assignments', ['phancong_id' => $phancong_id])
                 ->with('success', 'Nộp bài thành công!');
}


public function showProgramDetails()
{
    $userId = Auth::id();

    $lichThiList = LichThi::whereHas('phancong', function ($query) use ($userId) {
        $query->whereHas('enrollments', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    })->with(['phancong.hocPhan', 'diadiem'])->get();

    return view('frontend.hoctap.lichthi', compact('lichThiList'));
}
public function baiTapDaLam()
{
    $userId = auth()->id();

    // Giả sử bạn có bảng submissions hoặc bảng khác để xác định bài đã làm
    // Ở đây ta chỉ lấy assignment nào có quiz_id (coi như đã làm)
    $assignments = Assignment::whereNotNull('quiz_id')->get();

    $baiTaps = $assignments->map(function ($assignment) {
        if ($assignment->quiz_type === 'tu_luan') {
            $quiz = BoDeTuLuan::find($assignment->quiz_id);
        } elseif ($assignment->quiz_type === 'trac_nghiem') {
            $quiz = BoDeTracNghiem::find($assignment->quiz_id);
        } else {
            $quiz = null;
        }

        return [
            'assignment' => $assignment,
            'quiz' => $quiz,
            'type' => $assignment->quiz_type,
        ];
    });

    return view('frontend.hoctap.baitap_da_lam', compact('baiTaps'));
}
public function editBaiLam($type, $quiz_id)
{
    if ($type === 'trac_nghiem') {
        $quiz = BoDeTracNghiem::with('questions.options')->findOrFail($quiz_id);
    } elseif ($type === 'tu_luan') {
        $quiz = BoDeTuLuan::with('questions')->findOrFail($quiz_id);
    } else {
        abort(404);
    }

    return view('frontend.hoctap.bailam', compact('quiz', 'type'));
}
public function tienDoHocTap()
{
    $user = auth()->user();
    $student = $user->student;

    if (!$student) {
        return redirect()->back()->with('error', 'Không tìm thấy thông tin sinh viên.');
    }

    $nganh_id = $student->nganh_id;

    // Lấy chương trình đào tạo theo ngành
    $program = ChuongTrinhDaoTao::with(['programDetails.hocphan'])
        ->where('nganh_id', $nganh_id)
        ->first();

    if (!$program) {
        return redirect()->back()->with('error', 'Không tìm thấy chương trình đào tạo cho ngành của bạn.');
    }

    $totalCredits = $program->tong_tin_chi ?? 0;

    // Lấy các môn sinh viên đã học có điểm
    $completedEnrollments = $user->enrollments()
        ->whereHas('enrollResult', function ($q) {
            $q->whereNotNull('DiemChu');
        })
        ->with('enrollResult', 'phancong.hocphan')
        ->get();

    $completedCredits = $completedEnrollments->sum(function ($enrollment) {
        return $enrollment->phancong->hocphan->tinchi ?? 0;
    });

    $gpa = $completedEnrollments->avg(function ($enrollment) {
        return $enrollment->enrollResult->DiemHeSo4 ?? 0;
    });

    $percentComplete = $totalCredits > 0 ? round(($completedCredits / $totalCredits) * 100, 2) : 0;

    // Tính thống kê điểm danh theo từng học phần
    $hocPhanAttendanceStats = [];

    foreach ($completedEnrollments as $enrollment) {
        $phancong = $enrollment->phancong;
        if (!$phancong) continue;

        $hocPhan = $phancong->hocphan;

        // Lấy tất cả buổi học của phân công
        $tkbs = ThoiKhoaBieu::where('phancong_id', $phancong->id)->get();

        $presentCount = 0;
        $absentCount = 0;
        $lateCount = 0;

        foreach ($tkbs as $tkb) {
            $attendance = Attendance::where('tkb_id', $tkb->id)->first();

            if (!$attendance) continue;

            $userList = json_decode($attendance->user_list, true);

            if (is_array($userList)) {
                foreach ($userList as $userAttendance) {
                    if (($userAttendance['user_id'] ?? null) == $user->id) {
                        switch ($userAttendance['status'] ?? '') {
                            case 'present':
                                $presentCount++;
                                break;
                            case 'absent':
                                $absentCount++;
                                break;
                            case 'late':
                                $lateCount++;
                                break;
                        }
                        break;
                    }
                }
            }
        }

        $hocPhanAttendanceStats[] = [
            'hoc_phan' => $hocPhan,
            'present' => $presentCount,
            'absent' => $absentCount,
            'late' => $lateCount,
        ];
    }

    return view('frontend.hoctap.progress', compact(
        'completedCredits',
        'totalCredits',
        'gpa',
        'percentComplete',
        'completedEnrollments',
        'hocPhanAttendanceStats'
    ));
}

}
