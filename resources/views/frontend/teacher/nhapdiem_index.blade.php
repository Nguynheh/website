@extends('frontend.layouts.master')

@section('content')
<div class="container">
    <h3>Nhập điểm cho các lớp học phần</h3>

    @foreach($phancongs as $phancong)
        <div class="card mb-3">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $phancong->hocphan->title }}</strong> ({{ $phancong->hocphan->code }})
                </div>
                <div>
                    <!-- Nút Nhập điểm -->
                    <a href="{{ route('teacher.nhapdiem.form', ['phancong_id' => $phancong->id]) }}" class="btn btn-primary btn-sm">
                        Nhập điểm
                    </a>
                    <!-- Nút Xem điểm -->
                    <a href="{{ route('teacher.showDiem', ['phancong_id' => $phancong->id]) }}" class="btn btn-info btn-sm ml-2">
                        Xem điểm
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
<style>
    /* Container chính */
    .container {
        max-width: 800px;
        margin: 30px auto;
        padding: 0 15px;
    }

    /* Tiêu đề trang */
    h3 {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 25px;
        padding-bottom: 10px;
        border-bottom: 2px solid #3498db;
    }

    /* Card chứa thông tin lớp học phần */
    .card {
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        border: none;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .card-body {
        padding: 1.25rem 1.5rem;
    }

    /* Nội dung card */
    .card-body strong {
        font-size: 1.05rem;
        color: #34495e;
    }

    /* Nhóm nút */
    .ml-2 {
        margin-left: 0.75rem;
    }

    /* Nút bấm */
    .btn {
        border-radius: 6px;
        font-weight: 500;
        padding: 0.4rem 1rem;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .btn-sm {
        padding: 0.35rem 0.9rem;
        font-size: 0.85rem;
    }

    .btn-primary {
        background-color: #3498db;
        background-image: linear-gradient(to bottom, #3498db, #2980b9);
    }

    .btn-primary:hover {
        background-color: #2980b9;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(41, 128, 185, 0.3);
    }

    .btn-info {
        background-color: #2ecc71;
        background-image: linear-gradient(to bottom, #2ecc71, #27ae60);
        color: white;
    }

    .btn-info:hover {
        background-color: #27ae60;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(46, 204, 113, 0.3);
    }

    /* Responsive cho mobile */
    @media (max-width: 576px) {
        .card-body {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .ml-2 {
            margin-left: 0;
            margin-top: 0.5rem;
        }
        
        .btn {
            width: 100%;
            display: block;
        }
    }
</style>