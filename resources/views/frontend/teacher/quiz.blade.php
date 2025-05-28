@extends('frontend.layouts.master')

@section('content')
    <div class="card custom-card shadow-lg">
        <div class="card-body">
            <div class="header d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title text-center">Danh sách các bộ đề</h5>
                <a href="{{ route('teacher.quiz.create') }}" class="btn btn-success btn-create">+ Tạo bộ đề</a>
            </div>

            @foreach ($bodes as $bode)
                <div class="individual-bode mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0">{{ $bode->ten_de ?? $bode->title }}</h6>
                        <button type="button" class="delete-icon"
                            onclick="event.preventDefault(); 
                            if(confirm('Bạn chắc chắn muốn xóa bộ đề này?')) 
                                document.getElementById('delete-form-{{ $bode->id }}').submit();">
                            X
                        </button>
                        <form id="delete-form-{{ $bode->id }}" action="{{ route('teacher.quiz.destroy', $bode->id) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                    <div class="card-body">
                        <p class="card-text"><strong>Ngày tạo:</strong> {{ $bode->created_at->format('d/m/Y') }}</p>
                        <p class="card-text"><strong>Học phần:</strong> 
                            {{ $bode->hocphan->ten_hoc_phan ?? $bode->hocphan->title ?? 'Không xác định' }}
                        </p>
                        <p class="card-text"><strong>Loại bài tập:</strong> 
                            {{ $bode->type === 'tracnghiem' ? 'Trắc nghiệm' : ($bode->type === 'tuluan' ? 'Tự luận' : 'Không xác định') }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('styles')
<style>
    /* Container tổng */
    .custom-card {
        border-radius: 10px;
        padding: 20px;
        background-color: #fff;
    }

    .card-title {
        font-weight: 700;
        font-size: 1.5rem;
        color: #343a40;
    }

    /* Thẻ từng bộ đề */
    .individual-bode {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        transition: box-shadow 0.3s ease;
        background-color: #f8f9fa;
    }

    .individual-bode:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        background-color: #ffffff;
    }

    .individual-bode .card-header {
        background-color: #007bff;
        border-bottom: none;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        padding: 10px 15px;
        color: white;
    }

    .individual-bode .card-header .card-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin: 0;
    }

    .individual-bode .card-body {
        padding: 15px;
        color: #495057;
        font-size: 1rem;
    }

    /* Nút Tạo bộ đề */
    .btn-create {
        background-color: #198754;
        color: white;
        font-weight: 600;
        transition: background-color 0.3s ease;
        border-radius: 6px;
        padding: 8px 16px;
    }

    .btn-create:hover {
        background-color: #146c43;
        color: white;
    }

    /* Nút X để xóa */
    .delete-icon {
        background: none;
        border: none;
        font-size: 1.4rem;
        font-weight: bold;
        color: #dc3545;
        cursor: pointer;
        line-height: 1;
        transition: color 0.3s ease;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
    }

    .delete-icon:hover {
        color: #a71d2a;
        background-color: rgba(255, 255, 255, 0.2);
    }

    /* Responsive cho mobile */
    @media (max-width: 576px) {
        .individual-bode .card-header .card-title {
            font-size: 1rem;
        }

        .btn-create {
            font-size: 0.85rem;
            padding: 6px 12px;
        }

        .delete-icon {
            font-size: 1.2rem;
            width: 26px;
            height: 26px;
        }
    }
</style>
@endsection
