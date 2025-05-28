@extends('frontend.layouts.master')
@section('content')
    <div class="container training-program-container">
        <h4 class="program-title">Danh sách học phần</h4>
        
        @foreach($groupedDetails as $hocKy => $details)
            <h5 class="semester-title">Kỳ {{ $hocKy }}</h5>
            
            <table class="course-table">
                <thead>
                    <tr>
                        <th>Học phần</th>
                        <th>Loại</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($details as $detail)
                        <tr>
                            <td>{{ $detail->hocPhan->title }}</td>
                            <td>
                                <span class="course-type 
                                    @if($detail->loai === 'Bắt buộc') type-required
                                    @else type-elective
                                    @endif">
                                    {{ $detail->loai }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('hocphan.dangky') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="hoc_phan_id" value="{{ $detail->hocPhan->id }}">
                                    <button type="submit" class="register-btn">Đăng ký</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    </div>
@endsection

<style>
.training-program-container {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
    background-color: #f4f6f9;
    border-radius: 8px;
}

.program-title {
    color: #2c3e50;
    font-weight: 700;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #3498db;
    font-size: 1.5rem;
}

.semester-title {
    color: #2980b9;
    font-weight: 600;
    margin: 2rem 0 1rem 0;
    padding-left: 0.5rem;
    border-left: 4px solid #3498db;
    font-size: 1.25rem;
}

.course-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 2rem;
    background-color: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border: 1px solid #ddd;
    table-layout: fixed; /* Đảm bảo cột cố định độ rộng */
}

.course-table thead th,
.course-table tbody td {
    padding: 12px 15px;
    vertical-align: top;
    font-size: 0.95rem;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Thiết lập độ rộng cố định cho từng cột */
.course-table thead th:nth-child(1),
.course-table tbody td:nth-child(1) {
    width: 50%;
}

.course-table thead th:nth-child(2),
.course-table tbody td:nth-child(2) {
    width: 20%;
}

.course-table thead th:nth-child(3),
.course-table tbody td:nth-child(3) {
    width: 30%;
}

.course-table tbody tr:nth-child(even) {
    background-color: #f8fafc;
}

.course-table tbody tr:last-child td {
    border-bottom: none;
}

.course-type {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
    text-transform: capitalize;
}

.type-required {
    background-color: #e3f2fd;
    color: #1976d2;
}

.type-elective {
    background-color: #e8f5e9;
    color: #388e3c;
}

.register-btn {
    background-color: #2ecc71;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 8px 16px;
    font-weight: 500;
    font-size: 1rem;
    transition: background-color 0.2s, transform 0.1s ease;
    cursor: pointer;
}

.register-btn:hover {
    background-color: #27ae60;
    transform: translateY(-2px);
}

.register-btn:active {
    transform: translateY(0);
}
</style>

