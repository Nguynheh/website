@extends('frontend.layouts.master')

@section('content')
<div class="row">
    <!-- Sidebar: Danh sách lớp học phần -->
    <div class="col-md-3 col-lg-2 bg-light text-dark min-vh-100 p-3 border-end">
        <h5 class="fw-bold">📚 Lớp học phần</h5>
        <ul class="nav flex-column">
            @foreach ($phancongs as $pc)
                <li class="nav-item">
                    <a class="nav-link {{ request()->get('phancong_id') == $pc->id ? 'active fw-bold text-primary' : '' }}"
   href="{{ route('teacher.quanly', ['phancong_id' => $pc->id]) }}">
    {{ $pc->hocphan->title }} - {{ $pc->class->class_name ?? 'Chưa có lớp' }}
</a>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Main content -->
    <div class="col-md-9 col-lg-10">
        <div class="container">
            <h3 class="courses-title my-3">Quản lý </h3>

            @if($phancongActive)
            <!-- Tab Navigation -->
            <ul class="nav nav-tabs" id="teacherTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#diemDanh">📝 Điểm danh</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#thongKeDiemDanh">📊 Thống kê điểm danh</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#chiTietPhanCong">📄 Chi tiết Học phần</a>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content mt-3">
                <!-- TAB: Điểm danh -->
                <div class="tab-pane fade show active" id="diemDanh">
                    <p>Điểm danh cho lớp <strong>{{ $phancongActive->hocphan->title }}</strong>.</p>

                    <form id="diemdanhForm" method="POST">
                        @csrf

                        {{-- Chọn buổi học --}}
                        <div class="form-group mb-3">
                            <label for="tkb_id">Chọn buổi học:</label>
                            <select name="tkb_id" id="tkb_id" class="form-control" required>
                                <option value="">-- Chọn buổi --</option>
                                @foreach($tkbs as $tkb)
                                    <option value="{{ $tkb->id }}">
                                        {{ \Carbon\Carbon::parse($tkb->ngay)->format('d/m/Y') }} - Tiết {{ $tkb->tietdau }}-{{ $tkb->tietcuoi }} - Phòng {{ $tkb->diaDiem->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Bảng điểm danh sinh viên --}}
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    
                                    <th>Họ tên</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($students->count() > 0)
                                    @foreach($students as $index => $enrollment)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $enrollment->user->full_name ?? $enrollment->user->name }}</td>
                                            <td>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="user_list[{{ $enrollment->user_id }}]" value="present" required>
                                                    <label class="form-check-label">Có mặt</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="user_list[{{ $enrollment->user_id }}]" value="absent">
                                                    <label class="form-check-label">Vắng</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="user_list[{{ $enrollment->user_id }}]" value="late">
                                                    <label class="form-check-label">Muộn</label>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4">Không có sinh viên trong lớp học phần.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                        <button id="submitBtn" type="submit" class="btn btn-primary mt-3" >Lưu điểm danh</button>
                    </form>

                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            const form = document.getElementById('diemdanhForm');
                            const tkbSelect = document.getElementById('tkb_id');
                            const submitBtn = document.getElementById('submitBtn');

                            tkbSelect.addEventListener('change', function () {
                                const selectedId = this.value;
                                if (selectedId) {
                                    form.action = `/teacher/diemdanh/${selectedId}`;
                                    submitBtn.disabled = false;
                                } else {
                                    form.action = '#';
                                    submitBtn.disabled = true;
                                }
                            });
                        });
                    </script>
                </div>

                <!-- TAB: Lịch giảng dạy -->
                <div class="tab-pane fade" id="lichGiangDay">
                    <h5>Lịch giảng dạy</h5>
                    <ul class="list-group">
                        @foreach($tkbs as $tkb)
                            <li class="list-group-item">
                                {{ $tkb->phancong->hocphan->title }} - {{ \Carbon\Carbon::parse($tkb->ngay)->format('d/m/Y') }} 
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- TAB: Thống kê điểm danh -->
                <div class="tab-pane fade" id="thongKeDiemDanh">
                    <h5>📊 Thống kê điểm danh</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Ngày</th>
                                <th>Tiết</th>
                                <th>Phòng</th>
                                <th>Có mặt</th>
                                <th>Vắng</th>
                                <th>Muộn</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $att)
                                <tr>
<td>{{ optional($att->thoikhoabieu)->ngay ? \Carbon\Carbon::parse($att->thoikhoabieu->ngay)->format('d/m/Y') : 'Không có ngày' }}</td>
 <td>{{ optional($att->thoikhoabieu)->tietdau }} - {{ optional($att->thoikhoabieu)->tietcuoi }}</td>
<td>{{ optional(optional($att->thoikhoabieu)->diaDiem)->title ?? 'Không có địa điểm' }}</td>
<td>{{ $att->present_count }}</td>
<td>{{ $att->absent_count }}</td>
<td>{{ $att->late_count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- TAB: Chi tiết phân công -->    
                <div class="tab-pane fade" id="chiTietPhanCong">
                    <h5>📄 Chi tiết học phần</h5>
                                <div class="flex flex-wrap items-center gap-2 mb-4">
    <a href="{{ route('teacher.edit_noidung', $noidungPhancong->id ?? 0) }}"
       class="no-underline px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700">
        ✏️ Chỉnh sửa chi tiết học phần
    </a>

    <button type="button"
            class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded hover:bg-green-700"
            data-bs-toggle="modal"
            data-bs-target="#giaoBaiTapModal">
        📤 Giao bài tập
    </button>

    @if($phancongActive)
        <a href="{{ route('frontend.hoctap.chat', $phancongActive->id) }}"
           class="no-underline px-4 py-2 text-sm font-medium text-white bg-yellow-500 rounded hover:bg-yellow-600">
            💬 Chat lớp học phần
        </a>
    @endif
</div>



                    <p><strong>Học phần:</strong> {{ $phancongActive->hocphan->title }}</p>
                    <p><strong>Nội dung phân công:</strong> {{ $noidungPhancong ? $noidungPhancong->content : 'Không có nội dung.' }}</p>

                    <h6>🧠 Bộ đề tự luận</h6>
                    <ul>
                        @foreach($boDeTuLuan as $bode)
                            <li>{{ $bode->title }}</li>
                        @endforeach
                    </ul>

                    <h6>✅ Bộ đề trắc nghiệm</h6>
                    <ul>
                        @foreach($boDeTracNghiem as $bode)
                            <li>{{ $bode->title }}</li>
                        @endforeach
                    </ul>
                    <h6>📁 Tài nguyên</h6>
<ul>
    <label class="form-label">Tài liệu đã tải lên</label>
    @if($resources->isEmpty())
        <li>Không có tài liệu nào được tải lên.</li>
    @else
        <ul>
            @foreach($resources as $resource)
                <li>
                    <a href="{{ $resource->url }}" target="_blank">
    {{ $resource->title }}
</a>
                </li>
            @endforeach
        </ul>
    @endif
</ul>


    <!-- Form tải tài liệu -->
    <ul>
        <form action="{{ route('teacher.uploadResource', ['phancong_id' => $phancongActive->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="documents" class="form-label">Chọn tài liệu</label>
                <input type="file" name="documents[]" id="documents" multiple class="form-control" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.mp3,.mp4,.mov">
            </div>
            <button type="submit" class="btn btn-success">📤 Tải tài liệu lên</button>
        </form>
    </ul>

    <!-- Modal Giao Bài Tập -->
<div class="modal fade" id="giaoBaiTapModal" tabindex="-1" aria-labelledby="giaoBaiTapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('teacher.assign_exercise', ['phancong' => $phancongActive->id]) }}" method="POST">
            @csrf
            @if ($noidungPhancong)
                <input type="hidden" name="noidungphancong_id" value="{{ $noidungPhancong->id }}">

                @php
                    $tuluanIDs = json_decode($noidungPhancong->tuluan, true)['bodetuluan_ids'] ?? [];
                    $tracnghiemIDs = json_decode($noidungPhancong->tracnghiem, true)['bodetracnghiem_ids'] ?? [];

                    // Dùng để kiểm tra trùng ID giữa hai loại
                    $renderedIds = [];
                @endphp
            @else
                <div class="alert alert-warning m-3">❌ Không tìm thấy chi tiết học phần.</div>
            @endif

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="giaoBaiTapModalLabel">📤 Giao bài tập</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="bo_de_id" class="form-label">Chọn bộ đề cần giao</label>
                        <select name="bo_de_id" id="bo_de_id" class="form-select" required onchange="updateLoaiBoDe()">
                            <option value="">-- Chọn bộ đề --</option>

                            @foreach($boDeTuLuan as $bode)
                                @if (in_array($bode->id, $tuluanIDs) && !in_array($bode->id, $renderedIds))
                                    <option value="{{ $bode->id }}" data-type="tu_luan">📝 {{ $bode->title }} (Tự luận)</option>
                                    @php $renderedIds[] = $bode->id; @endphp
                                @endif
                            @endforeach

                            @foreach($boDeTracNghiem as $bode)
                                @if (in_array($bode->id, $tracnghiemIDs) && !in_array($bode->id, $renderedIds))
                                    <option value="{{ $bode->id }}" data-type="trac_nghiem">❓ {{ $bode->title }} (Trắc nghiệm)</option>
                                    @php $renderedIds[] = $bode->id; @endphp
                                @endif
                            @endforeach
                        </select>

                        <input type="hidden" name="loai_bo_de" id="loai_bo_de">
                    </div>

                    <div class="mb-3">
                        <label for="due_date" class="form-label">Hạn nộp</label>
                        <input type="datetime-local" name="due_date" id="due_date" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">✅ Giao bài</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">❌ Hủy</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function updateLoaiBoDe() {
        const select = document.getElementById('bo_de_id');
        const selectedOption = select.options[select.selectedIndex];
        const loaiBoDe = selectedOption.getAttribute('data-type');
        document.getElementById('loai_bo_de').value = loaiBoDe;
    }
</script>


    


            @else
                <p class="text-muted">Vui lòng chọn một lớp học phần ở bên trái để quản lý.</p>
            @endif
        </div>
    </div>
</div>
@endsection
