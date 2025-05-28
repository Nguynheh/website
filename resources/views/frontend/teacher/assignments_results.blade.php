@extends('frontend.layouts.master')

@section('css')
<!-- Giữ nguyên phần CSS tùy chỉnh của bạn nếu muốn -->
@endsection

@section('content')
<div class="submission-results-container py-4">
    <div class="container">
        <div class="card shadow submission-card">
            <div class="card-body">
                <div class="page-header mb-4">
                    <h1 class="page-title d-flex align-items-center">
                        <i class="fas fa-clipboard-check me-2 text-primary"></i> Kết quả nộp bài
                    </h1>
                </div>

                <div class="assignment-info mb-4 p-3 bg-light rounded">
                    <div class="row mb-2">
                        <div class="col-md-4"><strong>Loại:</strong> {{ $assignment->quiz_type == 'trac_nghiem' ? 'Trắc nghiệm' : 'Tự luận' }}</div>
                        <div class="col-md-4"><strong>Hạn nộp:</strong> {{ \Carbon\Carbon::parse($assignment->due_date)->format('d/m/Y H:i') }}</div>
                    </div>
                </div>

                @if($results->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>Sinh viên</th>
                                <th>Email</th>
                                <th>Điểm</th>
                                <th>Thời gian nộp</th>
                                @if ($assignment->quiz_type === 'tu_luan')
                                    <th>Hành động</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $submission)
                                <tr>
                                    <td>{{ optional($submission->student?->user)->full_name ?? 'Không có tên' }}</td>
                                    <td>{{ optional($submission->student?->user)->email ?? 'Không có email' }}</td>
                                    <td>
                                        <span class="badge rounded-pill {{ $submission->score !== null ? 'bg-success text-white' : 'bg-danger text-white' }}">
                                            {{ $submission->score ?? 'Chưa chấm' }}
                                        </span>
                                    </td>
                                    <td>{{ $submission->submitted_at ? \Carbon\Carbon::parse($submission->submitted_at)->format('d/m/Y H:i') : 'Chưa nộp' }}</td>
                                    @if ($assignment->quiz_type === 'tu_luan')
                                        <td>
                                            @if ($submission->submitted_at)
                                                <a href="{{ route('teacher.assignment.submission.view', [$assignment->id, $submission->id]) }}" 
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-pen-alt"></i> Chấm điểm
                                                </a>
                                            @else
                                                <span class="text-muted">Chưa nộp</span>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                        <h4 class="fw-bold">Chưa có bài nộp nào</h4>
                        <p class="text-muted">Học sinh chưa nộp bài cho bài tập này</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
