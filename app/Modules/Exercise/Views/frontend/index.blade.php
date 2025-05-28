@extends('frontend.hoctap.index')

@section('hoctap_content')
<div class="container mt-4">
    <h2 class="mb-3">Danh sách Bài Tập</h2>

    <!-- Danh sách Bộ Đề Trắc Nghiệm -->
    <h3 class="mt-4">Bộ Đề Trắc Nghiệm</h3>
    @if($boDeTracNghiem->count() > 0)
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Tên Bộ Đề</th>
                    <th>Học Phần</th>
                    <th>Thời Gian</th>
                    <th>Ngày Bắt Đầu</th>
                    <th>Ngày Kết Thúc</th>
                    <th>Điểm Tổng</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($boDeTracNghiem as $index => $boDe)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $boDe->title }}</td>
                    <td>{{ $boDe->hocPhan->title ?? 'N/A' }}</td>
                    <td>{{ $boDe->time }} phút</td>
                    <td>{{ $boDe->start_time ? date('d/m/Y H:i', strtotime($boDe->start_time)) : 'Chưa đặt' }}</td>
                    <td>{{ $boDe->end_time ? date('d/m/Y H:i', strtotime($boDe->end_time)) : 'Chưa đặt' }}</td>
                    <td>{{ $boDe->total_points }}</td>
                    <td>
                        <a href="{{ route('exercise.show', $boDe->id) }}" class="btn btn-info btn-sm">Xem</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-danger">Chưa có bộ đề trắc nghiệm nào.</p>
    @endif

    <!-- Danh sách Bộ Đề Tự Luận -->
    <h3 class="mt-5">Bộ Đề Tự Luận</h3>
    @if($boDeTuLuan->count() > 0)
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Tên Bộ Đề</th>
                    <th>Học Phần</th>
                    <th>Thời Gian</th>
                    <th>Ngày Bắt Đầu</th>
                    <th>Ngày Kết Thúc</th>
                    <th>Điểm Tổng</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($boDeTuLuan as $index => $boDe)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $boDe->title }}</td>
                    <td>{{ $boDe->hocPhan->title ?? 'N/A' }}</td>
                    <td>{{ $boDe->time }} phút</td>
                    <td>{{ $boDe->start_time ? date('d/m/Y H:i', strtotime($boDe->start_time)) : 'Chưa đặt' }}</td>
                    <td>{{ $boDe->end_time ? date('d/m/Y H:i', strtotime($boDe->end_time)) : 'Chưa đặt' }}</td>
                    <td>{{ $boDe->total_points }}</td>
                    <td>
                        <a href="{{ route('exercise.show', $boDe->id) }}" class="btn btn-info btn-sm">Xem</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-danger">Chưa có bộ đề tự luận nào.</p>
    @endif
</div>
@endsection
