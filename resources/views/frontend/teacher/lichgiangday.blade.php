<style>
    /* Main container */
    .teaching-schedule-container {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    /* Title styling */
    .schedule-title {
        color: #2c3e50;
        font-weight: 700;
        font-size: 1.8rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Register button */
    .register-btn {
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.8rem 1.5rem;
        font-size: 1rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.2);
    }

    .register-btn:hover {
        background: linear-gradient(135deg, #2980b9, #3498db);
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(52, 152, 219, 0.3);
    }

    /* Empty state */
    .empty-schedule {
        color: #95a5a6;
        font-style: italic;
        text-align: center;
        padding: 2rem;
        background-color: #f8f9fa;
        border-radius: 8px;
        margin-top: 1rem;
    }

    /* Schedule cards */
    .schedule-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .schedule-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .schedule-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
        border-color: #3498db;
    }

    /* Card header */
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.8rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .course-name {
        color: #2980b9;
        font-weight: 700;
        font-size: 1.1rem;
        margin: 0;
    }

    .schedule-date {
        color: #7f8c8d;
        font-size: 0.9rem;
        font-weight: 500;
    }

    /* Card details */
    .card-details {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.8rem;
        font-size: 0.95rem;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
    }

    .detail-label {
        color: #7f8c8d;
        font-weight: 500;
        font-size: 0.85rem;
        margin-bottom: 0.2rem;
    }

    .detail-value {
        color: #2c3e50;
        font-weight: 500;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .teaching-schedule-container {
            padding: 1.5rem;
        }
        
        .schedule-grid {
            grid-template-columns: 1fr;
        }
        
        .card-details {
            grid-template-columns: 1fr;
        }
        
        .schedule-title {
            font-size: 1.5rem;
        }
    }
</style>

@extends('frontend.layouts.master')
@section('content')
<div class="container teaching-schedule-container">
    <h1 class="schedule-title">
        <span>📚</span>
        Lịch giảng dạy
    </h1>

    <!-- Register button -->
    <div class="mb-6">
        <a href="{{ route('teacher.tkb.create') }}" class="register-btn">
            Đăng ký lịch giảng dạy
        </a>
    </div>

    @if($tkbs->isEmpty())
        <div class="empty-schedule">Không có lịch giảng dạy nào.</div>
    @else
        <div class="schedule-grid">
            @foreach($tkbs as $tkb)
                <div class="schedule-card">
                    <div class="card-header">
                        <h3 class="course-name">{{ $tkb->phancong->hocphan->title ?? '---' }}</h3>
                        <div class="schedule-date">
                            {{ \Carbon\Carbon::parse($tkb->ngay)->format('d/m/Y') }}
                        </div>
                    </div>

                    <div class="card-details">
                        <div class="detail-item">
                            <span class="detail-label">Buổi:</span>
                            <span class="detail-value">{{ $tkb->buoi }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Tiết:</span>
                            <span class="detail-value">{{ $tkb->tietdau }} - {{ $tkb->tietcuoi }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Địa điểm:</span>
                            <span class="detail-value">{{ $tkb->diaDiem->title ?? '---' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Học kỳ:</span>
                            <span class="detail-value">{{ $tkb->phancong->hocky->so_hoc_ky ?? '---' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Năm học:</span>
                            <span class="detail-value">{{ $tkb->phancong->namhoc->nam_hoc ?? '---' }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection