@extends('frontend.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-light text-dark min-vh-100 p-3 border-end">
            @include('frontend.layouts.sidebar') <!-- Gọi sidebar -->
        </div>

        <!-- Main Content -->
        <div class="col-md-9 completed-assignments-container">
            <h2 class="completed-title">
                <span>📝</span>
                Danh sách bài tập đã làm
            </h2>

            @forelse($baiTaps as $item)
                <div class="card assignment-card">
                    <div class="card-body assignment-card-body">
                        <div class="assignment-info">
                            <h5 class="assignment-title">
                                {{ $item['quiz']->title ?? '🔍 Không tìm thấy đề' }}
                            </h5>
                            <p class="assignment-detail">
                                <span class="detail-label">Loại bài tập:</span>
                                {{ $item['type'] === 'trac_nghiem' ? 'Trắc nghiệm' : 'Tự luận' }}
                            </p>
                            <p class="assignment-detail">
                                <span class="detail-label">Hạn nộp:</span>
                                {{ \Carbon\Carbon::parse($item['assignment']->due_date)->format('d/m/Y') }}
                            </p>
                        </div>
                        <div class="completion-status">
                            <span>✅</span>
                            <span>Hoàn thành</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-assignments">
                    Bạn chưa có bài tập nào đã làm.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
<style>
/* Tổng thể */
.completed-assignments-container {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    max-width: 900px;
    margin: 0 auto;
    padding: 2rem;
    background-color: #f5f7fa;
    border-radius: 10px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
}

/* Sửa lại background */
.container-fluid {
    padding: 0;
    background-color: #f5f7fa;
}

/* Title section */
.completed-title {
    color: #2c3e50;
    font-weight: 700;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 3px solid #3498db;
    font-size: 1.75rem;
    display: flex;
    align-items: center;
    gap: 12px;
}

/* Sidebar */
.sidebar {
    background-color: #fff;
    padding: 2rem 1rem;
    border-right: 2px solid #e9ecef;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    min-height: 100vh;
    width: 100%; /* Full width of sidebar */
}

/* Card bài tập */
.assignment-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    margin-bottom: 1.5rem;
    background-color: #ffffff;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

/* Hover effect for the card */
.assignment-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
}

/* Phần thông tin bên trái */
.assignment-info {
    flex: 1;
}

/* Card content */
.assignment-card-body {
    padding: 1.75rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* Completion status */
.completion-status {
    color: #27ae60;
    font-weight: 700;
    font-size: 1.1rem;
    padding-left: 1.5rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Thông báo khi không có bài tập */
.empty-assignments {
    background-color: #e3f2fd;
    color: #1976d2;
    border-radius: 8px;
    padding: 1.25rem;
    text-align: center;
    font-weight: 500;
    border-left: 4px solid #1976d2;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .sidebar {
        margin-bottom: 1.5rem;
        padding: 1.5rem;
    }

    .completed-assignments-container {
        padding: 1.5rem;
    }

    .completed-title {
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .assignment-card-body {
        flex-direction: column;
        align-items: flex-start;
    }

    .completion-status {
        margin-top: 1rem;
    }
}

</style>

