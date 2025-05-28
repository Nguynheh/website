@extends('frontend.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-light text-dark min-vh-100 p-3 border-end">
            @include('frontend.layouts.sidebar') <!-- Gọi sidebar -->
        </div>

        <!-- Main Content -->
        <div class="col-md-9 learning-detail-container">
            <h2 class="page-title">Chi Tiết Học Tập</h2>

            {{-- Lịch Thi --}}
            <div class="card exam-schedule-card">
                <div class="card-header exam-card-header">
                    <h5 class="exam-card-title">Lịch Thi</h5>
                </div>
                <div class="card-body exam-card-body">
                    @if($lichThiList->isEmpty())
                        <p class="no-exam-notice">Chưa có lịch thi nào được sắp xếp cho bạn.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table exam-table">
                                <thead>
                                    <tr>
                                        <th>Học phần</th>
                                        <th>Buổi</th>
                                        <th>Ngày 1</th>
                                        <th>Ngày 2</th>
                                        <th>Địa điểm</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lichThiList as $lich)
                                        <tr>
                                            <td>{{ $lich->phancong->hocphan->title ?? '---' }}</td>
                                            <td>{{ $lich->buoi }}</td>
                                            <td>{{ \Carbon\Carbon::parse($lich->ngay1)->format('d/m/Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($lich->ngay2)->format('d/m/Y') }}</td>
                                            <td>{{ $lich->diadiem->title ?? '---' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('style')
<style>
/* Tổng thể */
.exam-schedule-container {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    max-width: 800px;
    margin: 2rem auto;
    padding: 2rem;
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.exam-title {
    color: #2c3e50;
    font-weight: 700;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #3498db;
}

/* Bảng lịch thi */
.exam-schedule-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.exam-schedule-item {
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

.exam-schedule-item:hover {
    background-color: #e9f5ff;
    transform: translateX(5px);
}

.exam-schedule-link {
    color: #2980b9;
    font-weight: 500;
    text-decoration: none;
    flex-grow: 1;
    transition: color 0.2s;
}

.exam-schedule-link:hover {
    color: #3498db;
    text-decoration: underline;
}

/* Nút hủy đăng ký */
.unregister-exam-btn {
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

.unregister-exam-btn:hover {
    background-color: #c0392b;
    transform: translateY(-1px);
}

/* Thông báo không có lịch thi */
.no-exam-message {
    color: #7f8c8d;
    font-style: italic;
    text-align: center;
    padding: 2rem;
    background-color: #f8fafc;
    border-radius: 8px;
}

/* Responsive */
@media (max-width: 768px) {
    .exam-title {
        font-size: 1.5rem;
    }

    .exam-schedule-container {
        padding: 1rem;
    }
}
</style>
@endsection
