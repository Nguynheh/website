@extends('frontend.layouts.master')

@section('title', 'Thống kê điểm danh học phần')

@section('css')
<style>
    :root {
        --primary-color: #5e72e4;
        --secondary-color: #4a56d6;
        --success-color: #2dce89;
        --info-color: #11cdef;
        --warning-color: #fb6340;
        --danger-color: #f5365c;
        --light-color: #f8f9fe;
        --dark-color: #32325d;
        --border-radius: 0.5rem;
        --box-shadow: 0 4px 20px rgba(50, 50, 93, 0.15), 0 1px 6px rgba(0, 0, 0, 0.08);
        --transition: all 0.25s ease;
    }

    .attendance-stats {
        background-color: #f8f9fe;
        min-height: calc(100vh - 120px);
        padding: 2.5rem 0;
    }

    .stats-header {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .stats-title {
        color: var(--dark-color);
        font-weight: 700;
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .stats-title i {
        margin-right: 1rem;
        color: var(--primary-color);
        font-size: 1.5em;
    }

    .module-info {
        color: var(--dark-color);
        font-size: 1.1rem;
    }

    .module-id {
        background-color: var(--primary-color);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .alert-notification {
        border-radius: var(--border-radius);
        border-left: 4px solid;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
    }

    .alert-success {
        background-color: rgba(45, 206, 137, 0.1);
        border-left-color: var(--success-color);
        color: #1a7a56;
    }

    .alert-danger {
        background-color: rgba(245, 54, 92, 0.1);
        border-left-color: var(--danger-color);
        color: #a4133c;
    }

    .alert-notification i {
        margin-right: 0.75rem;
        font-size: 1.25rem;
    }

    .stats-table {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        overflow: hidden;
    }

    .table-responsive {
        border-radius: var(--border-radius);
        overflow: hidden;
    }

    .table {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table thead th {
        background-color: var(--primary-color);
        color: white;
        font-weight: 600;
        padding: 1rem 1.5rem;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.05em;
        border: none;
    }

    .table tbody tr {
        transition: var(--transition);
    }

    .table tbody tr:hover {
        background-color: #f6f9fc;
    }

    .table tbody td {
        padding: 1.25rem 1.5rem;
        vertical-align: middle;
        border-top: 1px solid #e9ecef;
    }

    .present-count {
        color: var(--success-color);
        font-weight: 600;
    }

    .absent-count {
        color: var(--danger-color);
        font-weight: 600;
    }

    .late-count {
        color: var(--warning-color);
        font-weight: 600;
    }

    .attendance-ratio {
        font-weight: 700;
    }

    .ratio-high {
        color: var(--success-color);
    }

    .ratio-medium {
        color: var(--warning-color);
    }

    .ratio-low {
        color: var(--danger-color);
    }

    .empty-state {
        padding: 3rem;
        text-align: center;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 3rem;
        color: #e9ecef;
        margin-bottom: 1rem;
    }

    .empty-state h4 {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
    }

    .btn-back {
        background-color: white;
        color: var(--primary-color);
        border: 1px solid var(--primary-color);
        border-radius: 50px;
        padding: 0.625rem 1.5rem;
        font-weight: 600;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        margin-top: 1.5rem;
    }

    .btn-back:hover {
        background-color: var(--primary-color);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(94, 114, 228, 0.3);
    }

    .btn-back i {
        margin-right: 0.5rem;
    }

    @media (max-width: 768px) {
        .table-responsive {
            border-radius: var(--border-radius);
            overflow-x: auto;
        }
        
        .stats-title {
            font-size: 1.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="attendance-stats">
    <div class="container">
        <div class="stats-header">
            <h1 class="stats-title">
                <i class="fas fa-chart-pie"></i> Thống kê điểm danh
            </h1>
            <div class="module-info">
                Học phần: <span class="module-id">ID {{ $hocphan_id }}</span>
            </div>
        </div>

        {{-- Hiển thị thông báo nếu có --}}
        @if(session('success'))
            <div class="alert-notification alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert-notification alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        @if(count($results) > 0)
            <div class="stats-table">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên sinh viên</th>
                                <th>Có mặt</th>
                                <th>Vắng</th>
                                <th>Trễ</th>
                                <th>Tổng buổi</th>
                                <th>Tỉ lệ chuyên cần</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $index => $student)
                                @php
                                    $total = $student['present'] + $student['absent'] + $student['late'];
                                    $ratio = $total > 0 ? round(($student['present'] / $total) * 100, 2) : 0;
                                    $ratioClass = $ratio >= 80 ? 'ratio-high' : ($ratio >= 50 ? 'ratio-medium' : 'ratio-low');
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $student['name'] }}</td>
                                    <td class="present-count">{{ $student['present'] }}</td>
                                    <td class="absent-count">{{ $student['absent'] }}</td>
                                    <td class="late-count">{{ $student['late'] }}</td>
                                    <td>{{ $total }}</td>
                                    <td>
                                        <span class="attendance-ratio {{ $ratioClass }}">
                                            {{ $ratio }}%
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-database"></i>
                <h4>Không có dữ liệu điểm danh</h4>
                <p>Không tìm thấy thông tin điểm danh cho học phần này</p>
            </div>
        @endif

        <a href="{{ url()->previous() }}" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>
</div>
@endsection