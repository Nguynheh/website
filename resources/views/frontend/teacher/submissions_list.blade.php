@extends('frontend.layouts.master')

@section('content')
<div class="container mt-5">
    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-file-earmark-text"></i> Danh sách bài nộp: {{ $assignment->title }}</h5>
            <span class="badge bg-light text-dark">{{ $submissions->count() }} bài nộp</span>
        </div>
        <div class="card-body">
            @if($submissions->isEmpty())
                <div class="alert alert-warning text-center">
                    <i class="bi bi-exclamation-circle"></i> Chưa có bài nộp nào cho bài tập này.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Sinh viên</th>
                                <th>Thời gian nộp</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($submissions as $submission)
                                <tr>
                                    <td>
                                        <i class="bi bi-person-circle me-1 text-primary"></i>
                                        {{ $submission->student->name }}
                                    </td>
                                    <td>
                                        <i class="bi bi-clock me-1 text-muted"></i>
                                        {{ $submission->submitted_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td>
                                        <a href="{{ route('frontend.teacher.submission_detail', $submission->id) }}"
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye-fill"></i> Xem chi tiết
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
