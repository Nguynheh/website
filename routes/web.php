<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Exercise\Controllers\BoDeTracNghiemController;
use App\Modules\Exercise\Controllers\BoDeTuLuanController;
use App\Http\Controllers\NotificationController;

Route::get('/', function () {
    return view('frontend.layouts.dashboard');
});
// Route::get('/admin', function () {
//     //xuly 
//     return view('backend.index');
// });
Route::get('/admin/login',[ \App\Http\Controllers\Auth\LoginController::class,'viewlogin'])->name('admin.login');
Route::post('/admin/login',[ \App\Http\Controllers\Auth\LoginController::class,'login'])->name('admin.checklogin');
 
Route::group( ['prefix'=>'admin/','middleware'=>'admin.auth', 'as'=>'admin.'],function(){
    Route::get('/',[ \App\Http\Controllers\AdminController::class,'index'])->name('home');
    Route::post('/logout',[ \App\Http\Controllers\Auth\LoginController::class,'logout'])->name('logout');
   
    Route::get('/dasboard', [App\Http\Controllers\AdminController::class, 'index'])->name('dasboard');

    //User section
    Route::resource('user', \App\Http\Controllers\UserController::class);
    Route::post('user_status',[\App\Http\Controllers\UserController::class,'userStatus'])->name('user.status');
    Route::get('user_search',[\App\Http\Controllers\UserController::class,'userSearch'])->name('user.search');
    Route::get('user_sort',[\App\Http\Controllers\UserController::class,'userSort'])->name('user.sort');
    Route::post('user_detail',[\App\Http\Controllers\UserController::class,'userDetail'])->name('user.detail');
    Route::post('user_profile',[\App\Http\Controllers\UserController::class,'userUpdateProfile'])->name('user.profileupdate');
    Route::get('user_profile',[\App\Http\Controllers\UserController::class,'userViewProfile'])->name('user.profileview');
   ///UGroup section
   Route::resource('ugroup', \App\Http\Controllers\UGroupController::class);
   Route::post('ugroup_status',[\App\Http\Controllers\UGroupController::class,'ugroupStatus'])->name('ugroup.status');
   Route::get('ugroup_search',[\App\Http\Controllers\UGroupController::class,'ugroupSearch'])->name('ugroup.search');

  ///Role section
  Route::resource('role', \App\Http\Controllers\RoleController::class);
  Route::post('role_status',[\App\Http\Controllers\RoleController::class,'roleStatus'])->name('role.status');
  Route::get('role_search',[\App\Http\Controllers\RoleController::class,'roleSearch'])->name('role.search');
  Route::get('role_function\{id}',[\App\Http\Controllers\RoleController::class,'roleFunction'])->name('role.function');
  Route::get('role_selectall\{id}',[\App\Http\Controllers\RoleController::class,'roleSelectall'])->name('role.selectall');
  
  Route::post('functionstatus',[\App\Http\Controllers\RoleController::class,'roleFucntionStatus'])->name('role.functionstatus');
  
    ///cfunction section
    Route::resource('cmdfunction', \App\Http\Controllers\CFunctionController::class);
    Route::post('cmdfunction_status',[\App\Http\Controllers\CFunctionController::class,'cmdfunctionStatus'])->name('cmdfunction.status');
    Route::get('cmdfunction_search',[\App\Http\Controllers\CFunctionController::class,'cmdfunctionSearch'])->name('cmdfunction.search');
   
    /// Setting  section
    Route::resource('setting', \App\Http\Controllers\SettingController::class);
       
    /////file upload/////////

    Route::post('avatar-upload', [\App\Http\Controllers\FilesController::class, 'avartarUpload' ])->name('upload.avatar');

    Route::post('product-upload', [\App\Http\Controllers\FilesController::class, 'productUpload' ])->name('upload.product');
    Route::post('upload-ckeditor', [\App\Http\Controllers\FilesController::class, 'ckeditorUpload' ])->name('upload.ckeditor');

   
});

// use App\Modules\Blog\Controllers\BlogController;
// use App\Modules\Blog\Controllers\BlogCategoryController;
// Route::group( ['prefix'=>'admin/'  , 'as' => 'admin.' ],function(){
   
//      ///BlogCategory section
//      Route::resource('blogcategory',  BlogCategoryController::class);
//      Route::post('blogcategory_status',[ BlogCategoryController::class,'blogcatStatus'])->name('blogcategory.status');
//      Route::get('blogcategory_search',[ BlogCategoryController::class,'blogcatSearch'])->name('blogcategory.search');
//      ///Blog section
//      Route::resource('blog', BlogController::class);
//      Route::post('blog_status',[ BlogController::class,'blogStatus'])->name('blog.status');
//      Route::get('blog_search',[ BlogController::class,'blogSearch'])->name('blog.search');
 
 
// });

Route::get('/', [App\Http\Controllers\Frontend\IndexController::class, 'home'])->name('home');

Route::get('front/login', [App\Http\Controllers\Frontend\IndexController::class, 'viewLogin'])->name('front.login');
Route::post('front/login', [App\Http\Controllers\Frontend\IndexController::class, 'login'])->name('front.login.submit');  
Route::get('front/register', [App\Http\Controllers\Frontend\IndexController::class, 'viewRegister'])->name('front.register');
Route::post('front/register', [App\Http\Controllers\Frontend\IndexController::class, 'saveUser'])->name('front.register.submit'); 


Route::post('/logout', function () {
    Auth::logout();
    return redirect('/'); // Chuyển hướng về trang chủ sau khi logout
})->name('logout');
use App\Http\Controllers\frontend\RoleSelectionController;

Route::middleware('auth')->group(function () {
    Route::get('/chon-vai-tro', [RoleSelectionController::class, 'showRoleSelection'])->name('chon.vai.tro');
    Route::post('/chon-vai-tro', [RoleSelectionController::class, 'saveRole'])->name('luu.vai.tro');
    Route::get('/chon-thong-tin', [RoleSelectionController::class, 'showRoleDetails'])->name('chon.thong.tin');
    Route::post('/chon-thong-tin', [RoleSelectionController::class, 'saveRoleDetails'])->name('luu.thong.tin');
});
use App\Http\Controllers\frontend\HoctapController;

Route::get('/chuong-trinh', [HocTapController::class, 'index'])->name('chuongtrinh.index');
// frontend.php
Route::post('/hocphan/dangky', [HocTapController::class, 'dangKyHocPhan'])->name('hocphan.dangky');
Route::get('/hoc-tap/da-dang-ky', [HocTapController::class, 'registered_courses'])->name('frontend.hoctap.registered_courses');
Route::get('/hocphan/{id}/chitiet', [HocTapController::class, 'chiTietHocPhan'])->name('frontend.hocphan.chitiet');
Route::delete('/hoc-phan/{id}/huy', [HoctapController::class, 'huyDangKy'])->name('frontend.hocphan.huy');

Route::prefix('student')->name('frontend.hoctap.')->group(function () {
    // Các route khác
    Route::get('assignments/{phancong_id}', [HocTapController::class, 'showAssignments'])->name('assignments');
});
Route::prefix('frontend')->name('frontend.')->group(function () {
    // Route để hiển thị quiz
    Route::get('student/quiz/{quizId}/{assignmentId}', [HocTapController::class, 'showQuiz'])->name('hoctap.quiz');
    
    // Route để nộp quiz
    Route::post('student/quiz/{quizId}/{assignmentId}/submit', [HocTapController::class, 'submitQuiz'])->name('hoctap.quiz.submit');
    
    // // Route để xem kết quả quiz
});
Route::get('/hoc-tap/tu-luan/{assignmentId}', [HoctapController::class, 'doTuLuan'])
    ->name('frontend.hoctap.tuluan');
Route::post('/hoc-tap/tu-luan/{assignmentId}/submit', [HoctapController::class, 'submitTuLuan'])
    ->name('frontend.hoctap.tuluan.submit');
Route::get('/quiz/{quizId}/result', [HocTapController::class, 'showQuizResult'])->name('frontend.hoctap.quiz.result');


Route::get('/baituluan/{id}', [BoDeTuLuanController::class, 'lamTuLuan'])->name('frontend.tuluan.lam');

Route::get('/thoi-khoa-bieu', [HocTapController::class, 'viewThoiKhoaBieuTheoTuan'])->name('thoikhoabieu.tuan');
Route::middleware(['auth'])->prefix('student')->group(function () {
    Route::get('/hoc-tap', [HocTapController::class, 'showProgramDetails'])->name('hoctap.lichhoc');
});
Route::get('/hoc-tap/bai-tap-da-lam', [HoctapController::class, 'baiTapDaLam'])->name('hoctap.baitap.dalam');
Route::get('/hoc-tap/bai-lam/{type}/{quiz}', [HoctapController::class, 'editBaiLam'])
    ->name('hoctap.bailam.edit');

Route::put('/hoc-tap/bai-lam/{type}/{quiz}', [HoctapController::class, 'updateBaiLam'])
    ->name('hoctap.bailam.update');

    Route::get('/tien-do-hoc-tap', [HocTapController::class, 'tienDoHocTap'])->name('hoctap.progress');

Route::get('/teacher/quan-ly', [\App\Http\Controllers\frontend\TeacherController::class, 'quanLiGiangVien'])->name('teacher.quanly');
Route::post('/teacher/upload-resource/{phancong_id}', [\App\Http\Controllers\frontend\TeacherController::class, 'uploadResource'])->name('teacher.uploadResource');
Route::post('/teacher/add-assignment', [\App\Http\Controllers\frontend\TeacherController::class, 'addAssignment'])->name('teacher.addAssignment');

Route::get('teacher/lich-giang-day', [\App\Http\Controllers\frontend\TeacherController::class, 'lichgiangday'])->name('teacher.lichgiangday');

Route::middleware(['auth'])->group(function () {
    Route::get('/dangki-lich', [\App\Http\Controllers\frontend\TeacherController::class, 'createTkb'])->name('teacher.tkb.create');
    Route::post('/dangki-lich', [\App\Http\Controllers\frontend\TeacherController::class, 'storeTkb'])->name('teacher.tkb.store');
});
Route::post('/teacher/diemdanh', [\App\Http\Controllers\frontend\TeacherController::class, 'diemDanh'])->name('teacher.diemdanh');


Route::middleware(['auth'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::post('/diemdanh/{tkb_id}', [\App\Http\Controllers\frontend\TeacherController::class, 'saveDiemDanh'])->name('diemdanh.save');
});
Route::get('/teacher/diemdanh/thongke/{hocphan_id}', [\App\Http\Controllers\frontend\TeacherController::class, 'thongKeDiemDanh'])->name('teacher.diemdanh.thongke');


Route::middleware(['auth'])->prefix('teacher')->group(function () {
    // Route cho trang danh sách bài tập
    Route::get('/assignments', [\App\Http\Controllers\frontend\TeacherController::class, 'assignments'])->name('frontend.teacher.assignments');

    // Route cho trang tạo bài tập mới
    Route::get('/assignments/create', [\App\Http\Controllers\frontend\TeacherController::class, 'createAssignment'])->name('frontend.teacher.assignments.create');
    
    // Route cho việc lưu bài tập mới
    Route::post('/assignments', [\App\Http\Controllers\frontend\TeacherController::class, 'storeAssignment'])->name('frontend.teacher.assignments.store');
    Route::get('/assignments/{id}/edit', [\App\Http\Controllers\frontend\TeacherController::class, 'editAssignment'])->name('frontend.teacher.editassignment');
    Route::put('/assignments/{id}', [\App\Http\Controllers\frontend\TeacherController::class, 'updateAssignment'])->name('frontend.teacher.assignments.update');
    Route::delete('/assignments/{id}', [\App\Http\Controllers\frontend\TeacherController::class, 'destroyAssignment'])->name('frontend.teacher.assignments.destroy');
});
Route::get('teacher/submissions/{assignment_id}', [\App\Http\Controllers\frontend\TeacherController::class, 'listSubmissions'])->name('frontend.teacher.submissions_list');
Route::post('/teacher/submission/{submissionId}/auto-grade', [\App\Http\Controllers\frontend\TeacherController::class, 'autoGrade'])->name('frontend.teacher.autoGrade');
Route::post('/teacher/assignments/{assignmentId}/submissions/{submissionId}/grade', 
    [\App\Http\Controllers\frontend\TeacherController::class, 'gradeTuluanSubmission'])
    ->name('teacher.grade.tuluan');Route::get('teacher/submissions/{submission_id}', [\App\Http\Controllers\frontend\TeacherController::class, 'showSubmissionDetail'])->name('teacher.submissions.show');

Route::get('teacher/notifications', [\App\Http\Controllers\frontend\TeacherController::class, 'notifications'])->name('frontend.teacher.notifications');

Route::get('/teacher/notifications/create/{group_id}', [\App\Http\Controllers\frontend\TeacherController::class, 'createNotificationForm'])->name('frontend.teacher.notifications.create');

Route::post('teacher/notifications/send/{group_id}', [\App\Http\Controllers\frontend\TeacherController::class, 'sendNotification'])->name('frontend.teacher.notifications.send');


Route::get('teacher/class-details/{phancong_id}', [\App\Http\Controllers\frontend\TeacherController::class, 'showSubmissionDetail'])->name(name: 'teacher.class_details');
Route::post('/teacher/assign-exercise/{phancong}', [\App\Http\Controllers\frontend\TeacherController::class, 'assignExercise'])->name('teacher.assign_exercise');

Route::prefix('teacher')->name('teacher.')->group(function () {
    Route::get('teaching-content/{id}/edit', [\App\Http\Controllers\frontend\TeacherController::class, 'editNoiDungPhanCong'])->name('edit_noidung');
    
    // Change the POST method to PUT
    Route::put('teaching-content/{id}/update', [\App\Http\Controllers\frontend\TeacherController::class, 'updateContent'])->name('edit_noidung.update');
});
Route::delete('/noidung-phancong/{noidungPhancongId}/resource/{resourceId}', [\App\Http\Controllers\frontend\TeacherController::class, 'destroyResource'])
->name('noidung_phancong.resource.destroy');
Route::middleware(['auth'])->group(function () {
    Route::get('/teacher/bode', [\App\Http\Controllers\frontend\TeacherController::class, 'indexQuiz'])->name('teacher.quiz');
});
Route::get('/bode/{id}', action: [\App\Http\Controllers\frontend\TeacherController::class, 'showQuiz'])->name('teacher.quiz.show');
Route::prefix('teacher')->name('teacher.')->group(function () {
    Route::put('/quiz/{id}', [\App\Http\Controllers\frontend\TeacherController::class, 'updateQuiz'])->name('quiz.update');
});
Route::delete('/teacher/quiz/{id}', [\App\Http\Controllers\frontend\TeacherController::class, 'destroyQuiz'])->name('teacher.quiz.destroy');

Route::get('/teacher/quiz/create', [\App\Http\Controllers\frontend\TeacherController::class, 'createQuiz'])->name('teacher.quiz.create');
Route::post('/teacher/quiz/store', [\App\Http\Controllers\frontend\TeacherController::class, 'storeQuiz'])->name('teacher.quiz.store');
Route::post('/teacher/quiz/add-question', [\App\Http\Controllers\frontend\TeacherController::class, 'storeQuestion'])->name(name: 'teacher.quiz.storeQuestion');

Route::get('/teacher/exam-schedule', [\App\Http\Controllers\frontend\TeacherController::class, 'examSchedulePage'])->name('teacher.exam_schedule.index');

Route::prefix('teacher')->middleware(['auth'])->group(function () {
    Route::get('/lich-thi/create', [\App\Http\Controllers\frontend\TeacherController::class, 'createExamSchedule'])->name('teacher.exam_schedule.create');
    Route::post('/lich-thi/store', [\App\Http\Controllers\frontend\TeacherController::class, 'storeExamSchedule'])->name('teacher.exam_schedule.store');
});
Route::get('/teacher/nhapdiem', [\App\Http\Controllers\frontend\TeacherController::class, 'nhapDiemIndex'])->name('teacher.nhapdiem.index');
Route::get('/teacher/nhapdiem/{phancong_id}', [\App\Http\Controllers\frontend\TeacherController::class, 'showNhapDiemForm'])->name('teacher.nhapdiem.form');
Route::post('/teacher/nhapdiem/{phancong_id}', [\App\Http\Controllers\frontend\TeacherController::class, 'saveNhapDiem'])->name('teacher.nhapdiem.save');

Route::get('/teacher/diem/{phancong_id}', [\App\Http\Controllers\frontend\TeacherController::class, 'showDiem'])->name('teacher.showDiem');


Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('hoctap.notifications');
Route::get('notifications/read/{notification}', [NotificationController::class, 'markAsRead'])->name('notifications.read');
});
Route::get('teacher/assignments', [\App\Http\Controllers\frontend\TeacherController::class, 'viewAssignments'])->name('teacher.assignments');
Route::get('teacher/assignments/{assignmentId}', [\App\Http\Controllers\frontend\TeacherController::class, 'viewAssignmentResults'])->name('teacher.assignment.results');
Route::get('/teacher/assignment/{assignmentId}/submission/{submissionId}', [\App\Http\Controllers\frontend\TeacherController::class, 'viewStudentSubmission'])->name('teacher.assignment.submission.view');
Route::post('/teacher/assignment/{assignmentId}/submission/{submissionId}', [\App\Http\Controllers\frontend\TeacherController::class, 'gradeTuluanSubmission'])->name('teacher.assignment.submission.grade');
use App\Http\Controllers\frontend\ProfileController;

Route::middleware(['auth'])->group(function () {
    Route::get('/ho-so', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/ho-so/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/ho-so/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::post('/ho-so/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});
Route::delete('/teacher/resource/{id}', [\App\Http\Controllers\frontend\TeacherController::class, 'destroy'])
    ->name('teacher.resource.delete');
    Route::post('/teacher/resource/upload', [\App\Http\Controllers\frontend\TeacherController::class, 'upload'])->name('teacher.resource.upload');
use App\Http\Controllers\MessageController;

Route::get('chat/{phancong_id}', [MessageController::class, 'index'])->name('frontend.hoctap.chat');
Route::post('chat/{phancong_id}', [MessageController::class, 'store'])->name('frontend.hoctap.chat.store');
