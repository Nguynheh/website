@extends('frontend.layouts.master')

@section('content')
<div class="container">
    <div class="custom-score-wrapper">
        <h3>Danh sách điểm cho lớp học phần: {{ $phancong->hocphan->title }}</h3>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Họ tên</th>
                    <th>DiemBP</th>
                    <th>Thi1</th>
                    <th>Thi2</th>
                    <th>Diem1</th>
                    <th>Diem2</th>
                    <th>DiemMax</th>
                    <th>DiemChu</th>
                    <th>DiemHeSo4</th>
                </tr>
            </thead>
            <tbody>
                @foreach($phancong->enrollments as $enrollment)
                    @php $result = $enrollment->enrollResult; @endphp
                    <tr>
                        <td>{{ $enrollment->user->full_name ?? 'N/A' }}</td>
                        <td>{{ $result->DiemBP ?? 'Chưa có điểm' }}</td>
                        <td>{{ $result->Thi1 ?? 'Chưa có điểm' }}</td>
                        <td>{{ $result->Thi2 ?? 'Chưa có điểm' }}</td>
                        <td>{{ $result->Diem1 ?? 'Chưa có điểm' }}</td>
                        <td>{{ $result->Diem2 ?? 'Chưa có điểm' }}</td>
                        <td>{{ $result->DiemMax ?? 'Chưa có điểm' }}</td>
                        <td>{{ $result->DiemChu ?? 'Chưa có điểm' }}</td>
                        <td>{{ $result->DiemHeSo4 ?? 'Chưa có điểm' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style>
    .custom-score-wrapper {
        margin: 30px auto;
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
    }

    .custom-score-wrapper h3 {
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 25px;
        padding-bottom: 10px;
        border-bottom: 2px solid #3498db;
        text-align: center;
    }

    .table {
        background-color: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
    }

    .table thead th {
        background-color: #3498db;
        color: white;
        font-weight: 500;
        text-align: center;
        vertical-align: middle;
        border-bottom: 2px solid #2980b9;
    }

    .table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    .table tbody tr:hover {
        background-color: #e9f7fe;
    }

    .table td {
        text-align: center;
        vertical-align: middle;
        font-weight: 500;
    }

    .table td:first-child {
        text-align: left;
        font-weight: 600;
        color: #2c3e50;
    }

    .table td:empty::after {
        content: 'Chưa có điểm';
        color: #e74c3c;
        font-style: italic;
    }

    @media (max-width: 768px) {
        .custom-score-wrapper {
            padding: 15px;
        }

        .table {
            display: block;
            overflow-x: auto;
        }

        .table th, .table td {
            font-size: 0.9rem;
            padding: 10px;
        }
    }
</style>
@endsection
