    @extends('frontend.layouts.master')

    @section('content')
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-light text-dark min-vh-100 p-3 border-end">
            @include('frontend.layouts.sidebar')
        </div>

        <!-- Main content -->
        <div class="col-md-9 col-lg-10">
            <div class="container registered-courses-container">
                <h3 class="courses-title">Danh sách học phần đã đăng ký</h3>

                @if($enrollments->isEmpty())
                    <p class="no-courses-message">Bạn chưa đăng ký học phần nào.</p>
                @else
                    <ul class="courses-list">
                        @foreach($enrollments as $enrollment)
                            @php
        $hocphan = optional($enrollment->phancong)->hocphan;
        $diemChu = optional($enrollment->enrollResult)->DiemChu;

    @endphp


                            <li class="course-item">
                                @if ($hocphan)
        <a href="{{ route('frontend.hocphan.chitiet', $hocphan->id) }}">
            {{ $hocphan->title }}
        </a>
    @else
        <span class="text-danger">Học phần không tồn tại hoặc đã bị xóa</span>
    @endif

                                @if ($diemChu)
                                    <span class="text-muted" style="margin-left: 1rem; font-style: italic;">
                                        <i class="fas fa-check-circle text-success"></i> Đã hoàn thành ({{ $diemChu }})
                                    </span>
                                @else
                                    <form action="{{ route('frontend.hocphan.huy', optional($hocphan)->id) }}" method="POST"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn hủy đăng ký học phần này không?');"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-times-circle"></i> Hủy đăng ký
                                        </button>
                                    </form>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
    @endsection

    <style>
        /* Tổng thể */
    .registered-courses-container {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        max-width: 800px;
        margin: 2rem auto;
        padding: 2rem;
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .courses-title {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #3498db;
    }

    /* Danh sách học phần */
    .courses-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .course-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        margin-bottom: 0.75rem;
        background-color: #f8fafc;
        border-radius: 8px;
        transition: all 0.2s ease;
        border-left: 4px solid #3498db;
    }

    .course-item:hover {
        background-color: #e9f5ff;
        transform: translateX(5px);
    }

    .course-link {
        color: #2980b9;
        font-weight: 500;
        text-decoration: none;
        flex-grow: 1;
        transition: color 0.2s;
    }

    .course-link:hover {
        color: #3498db;
        text-decoration: underline;
    }

    /* Nút hủy đăng ký */
    .unregister-btn {
        background-color: #e74c3c;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-left: 1rem;
    }

    .unregister-btn:hover {
        background-color: #c0392b;
        transform: translateY(-1px);
    }

    /* Thông báo không có học phần */
    .no-courses-message {
        color: #7f8c8d;
        font-style: italic;
        text-align: center;
        padding: 2rem;
        background-color: #f8fafc;
        border-radius: 8px;
    }

    /* Sidebar */
    .nav.flex-column .nav-item {
        margin-bottom: 1rem;
    }

    .nav.flex-column .nav-link {
        color: rgb(16, 20, 25);
        font-weight: 500;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        background-color: transparent;
    }

    .nav.flex-column .nav-link:hover {
        background-color: #d0e8ff;
        color: #1e3a8a;
        text-decoration: none;
    }

    .nav.flex-column .nav-link.active {
        background-color: #a7d4f8;
        color: #0f172a;
    }

    .bg-white {
        background-color: #ffffff;
    }

    .border {
        border: 1px solid #e9ecef;
    }

    .p-3 {
        padding: 1rem;
    }

    .mb-3 {
        margin-bottom: 1rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .courses-title {
            font-size: 1.5rem;
        }

        .registered-courses-container {
            padding: 1rem;
        }

        .col-md-3 {
            margin-bottom: 1rem;
        }
    }
    </style>