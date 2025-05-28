@extends('frontend.layouts.master')

@section('content')
<div class="container-custom">
    <h3>Nhập điểm học phần: {{ $phancong->hocphan->title }}</h3>

    <form method="POST" action="{{ route('teacher.nhapdiem.save', $phancong->id) }}">
        @csrf
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Họ tên</th>
                    <th>DiemBP</th>
                    <th>Thi1</th>
                    <th>Thi2</th>
                </tr>
            </thead>
            <tbody>
                @foreach($phancong->enrollments as $enrollment)
                    @php $result = $enrollment->enrollResult; @endphp
                    <tr>
                        <td>{{ $enrollment->user->full_name ?? 'N/A' }}</td>
                        <input type="hidden" name="students[{{ $enrollment->id }}][student_id]" value="{{ $enrollment->user->student->id }}">
                        <td>
                            <input type="number" step="0.01" name="students[{{ $enrollment->id }}][DiemBP]" value="{{ $result->DiemBP ?? '' }}">
                        </td>
                        <td>
                            <input type="number" step="0.01" name="students[{{ $enrollment->id }}][Thi1]" value="{{ $result->Thi1 ?? '' }}">
                        </td>
                        <td>
                            <input type="number" step="0.01" name="students[{{ $enrollment->id }}][Thi2]" value="{{ $result->Thi2 ?? '' }}">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" class="btn btn-primary">Lưu điểm</button>
    </form>
</div>
@endsection

<style>
/* Container chính */
.container-custom {
    max-width: 1200px;
    margin: 30px auto;
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
}

/* Tiêu đề trang */
h3 {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 25px;
    padding-bottom: 10px;
    border-bottom: 2px solid #3498db;
}

/* Bảng điểm */
.table {
    width: 100%;
    margin-bottom: 1.5rem;
    background-color: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
}

.table-bordered {
    border: 1px solid #dee2e6;
}

/* Header bảng */
.table thead th {
    background-color: #3498db;
    color: white;
    font-weight: 500;
    padding: 12px 15px;
    text-align: center;
    vertical-align: middle;
    border-bottom: 2px solid #2980b9;
}

/* Dòng trong bảng */
.table tbody tr {
    transition: background-color 0.2s;
}

.table tbody tr:nth-child(even) {
    background-color: #f8f9fa;
}

.table tbody tr:hover {
    background-color: #e9f7fe;
}

/* Ô trong bảng */
.table td {
    padding: 12px 15px;
    vertical-align: middle;
    border-top: 1px solid #dee2e6;
}

/* Ô input điểm */
.table input[type="number"] {
    width: 70px;
    padding: 6px 10px;
    border: 1px solid #ced4da;
    border-radius: 4px;
    text-align: center;
    transition: border-color 0.3s;
}

.table input[type="number"]:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
    outline: none;
}

/* Nút Lưu điểm */
.btn-primary {
    background-color: #3498db;
    border-color: #3498db;
    padding: 10px 25px;
    font-weight: 500;
    border-radius: 6px;
    transition: all 0.3s;
    float: right;
    margin-top: 20px;
}

.btn-primary:hover {
    background-color: #2980b9;
    border-color: #2980b9;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(41, 128, 185, 0.3);
}

/* Responsive cho mobile */
@media (max-width: 768px) {
    .container-custom {
        padding: 15px;
    }

    .table {
        display: block;
        overflow-x: auto;
    }

    .table thead th,
    .table td {
        padding: 8px 10px;
        font-size: 0.9rem;
    }

    .table input[type="number"] {
        width: 60px;
        padding: 4px 8px;
    }

    .btn-primary {
        width: 100%;
        float: none;
    }
}
</style>
