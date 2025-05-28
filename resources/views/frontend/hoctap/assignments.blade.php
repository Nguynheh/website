@extends('frontend.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-light text-dark min-vh-100 p-3 border-end">
            @include('frontend.layouts.sidebar') <!-- Gọi sidebar -->
        </div>

        <!-- Nội dung -->
        <div class="col-md-9 col-lg-10">
            <div class="container assignment-container">
                <h3 class="assignment-list-title">Danh sách bài tập</h3>

                @foreach($assignments as $assignment)
                    <div class="card assignment-card" data-aos="fade-up" data-aos-duration="1000">
                        <div class="card-header assignment-card-header">
                            <h5 class="assignment-card-title">{{ $assignment->title }}</h5>
                        </div>
                        <div class="card-body assignment-card-body">
                          <p class="assignment-content">
    <span class="content-label">Nội dung:</span>
    @if ($assignment->quiz_type === 'tu_luan')
        {{ $assignment->quizTuLuan->title ?? 'Không có tiêu đề' }}
    @elseif ($assignment->quiz_type === 'trac_nghiem')
        {{ $assignment->quizTracNghiem->title ?? 'Không có tiêu đề' }}
    @else
        Không xác định loại bài quiz
    @endif
</p>

                            {{-- Link làm bài tập --}}
                            @if ($assignment->quiz_type === 'trac_nghiem')
                                <a href="{{ route('frontend.hoctap.quiz', ['quizId' => $assignment->quiz_id, 'assignmentId' => $assignment->id]) }}" 
                                   class="quiz-btn tracnghiem-btn">
                                    <i class="fas fa-pencil-alt"></i> Làm bài trắc nghiệm
                                </a>
                            @elseif ($assignment->quiz_type === 'tu_luan')
                                <a href="{{ route('frontend.hoctap.tuluan', ['assignmentId' => $assignment->id]) }}" 
                                   class="quiz-btn tuluan-btn">
                                    <i class="fas fa-edit"></i> Làm bài tự luận
                                </a>
                            @else
                                <p class="no-assignment">Chưa có bài tập nào.</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Thêm Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Thêm Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<!-- Thêm AOS CSS -->
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

<!-- Thêm CSS tùy chỉnh -->
<style>
    /* Tổng thể */
    .assignment-container {
        max-width: 1200px;
        margin: 3rem auto;
        padding: 2rem;
        background-color: #f9f9f9;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .assignment-list-title {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 3px solid #3498db;
    }

    /* Card bài tập */
    .assignment-card {
        margin-bottom: 1.5rem;
        border-radius: 10px;
        transition: all 0.3s ease;
        background-color: #ffffff;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
    }

    .assignment-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 30px rgba(0, 0, 0, 0.15);
    }

    .assignment-card-header {
        background-color: #f1f3f5;
        padding: 1rem;
        border-bottom: 1px solid #ddd;
    }

    .assignment-card-title {
        font-size: 1.25rem;
        color: #2c3e50;
        font-weight: bold;
    }

    .assignment-card-body {
        padding: 1.5rem;
    }

    .assignment-content {
        color: #7f8c8d;
        font-size: 1rem;
        margin-bottom: 1.5rem;
    }

    .content-label {
        font-weight: bold;
        color: #2c3e50;
    }

    /* Nút làm bài trắc nghiệm và tự luận */
    .quiz-btn {
        display: inline-block;
        padding: 0.75rem 2rem;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 50px;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        text-align: center;
    }

    .quiz-btn:hover {
        opacity: 0.9;
    }

    .tracnghiem-btn {
        background-color: #2980b9;
    }

    .tracnghiem-btn:hover {
        background-color: #1c5a88;
    }

    .tuluan-btn {
        background-color: #27ae60;
    }

    .tuluan-btn:hover {
        background-color: #1e7e48;
    }

    /* Thông báo không có bài tập */
    .no-assignment {
        color: #e74c3c;
        font-style: italic;
    }

    /* Sidebar */
    .min-vh-100 {
        min-height: 100vh;
    }
</style>

<!-- Thêm AOS JS -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init();
    });
</script>
