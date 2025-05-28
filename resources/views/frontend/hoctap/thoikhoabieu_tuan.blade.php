@extends('frontend.layouts.master')

@section('head')
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<!-- Font Awesome (icon) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endsection

@section('content')
@php
    use Carbon\Carbon;
    $prevWeek = $startOfWeek->copy()->subWeek()->toDateString();
    $nextWeek = $startOfWeek->copy()->addWeek()->toDateString();
@endphp

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-light text-dark min-vh-100 p-3 border-end">
            @include('frontend.layouts.sidebar') <!-- Gọi sidebar -->
        </div>

        <!-- Nội dung lịch học -->
        <div class="col-md-9 col-lg-10">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="{{ route('thoikhoabieu.tuan', ['start' => $prevWeek]) }}" class="btn btn-outline-primary">
                        <i class="fas fa-chevron-left"></i> Tuần trước
                    </a>

                    <h1 class="schedule-title m-0">
                        {{ $startOfWeek->format('d/m') }} - {{ $endOfWeek->format('d/m/Y') }}
                    </h1>

                    <a href="{{ route('thoikhoabieu.tuan', ['start' => $nextWeek]) }}" class="btn btn-outline-primary">
                        Tuần sau <i class="fas fa-chevron-right"></i>
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table schedule-table">
                        <thead class="table-header">
                            <tr>
                                <th class="time-slot" style="width: 120px;"></th>
                                @foreach (range(1, 7) as $thu)
                                    <th>
                                        {{ \Carbon\Carbon::parse($startOfWeek)->addDays($thu - 1)->translatedFormat('l') }}
                                        <br>
                                        {{ \Carbon\Carbon::parse($startOfWeek)->addDays($thu - 1)->format('d/m') }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (['Morning' => [1, 4], 'Afternoon' => [5, 7], 'Evening' => [8, 10]] as $session => [$startTiet, $endTiet])
                                <tr>
                                    <td class="time-slot">{{ $session }}</td>
                                    @foreach (range(1, 7) as $thu)
                                        @php
                                            $ngay = $startOfWeek->copy()->addDays($thu - 1);
                                            $tkb = $tkbTrongTuan->first(function ($item) use ($startTiet, $endTiet, $ngay) {
                                                return $item->tietdau <= $endTiet && $item->tietcuoi >= $startTiet && $item->ngay === $ngay->toDateString();
                                            });
                                        @endphp
                                        <td>
                                            @if ($tkb)
                                                <div class="class-card 
                                                    @if($session === 'Morning') morning-class
                                                    @elseif($session === 'Afternoon') afternoon-class
                                                    @else evening-class
                                                    @endif">
                                                    <div class="class-title">
                                                        {{ $tkb->phancong->hocPhan->title ?? '---' }}
                                                    </div>
                                                    <div class="class-details">
                                                        {{ $tkb->diaDiem->title ?? '---' }}<br>
                                                        Giảng viên: {{ $tkb->phancong->giangvien->user->full_name ?? '' }}
                                                    </div>
                                                </div>
                                            @else
                                                <div class="empty-slot">-</div>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('style')
<style>


.schedule-title {
    color: #2c3e50;
    font-weight: 700;
    margin-bottom: 1.5rem;
    text-align: center;
    font-size: 1.8rem;
    position: relative;
    margin-top: 5px;
}

.schedule-title::after {
    content: "";
    position: absolute;
    bottom: -8px;
    left: 50%;
    width: 60px;
    height: 4px;
    background-color: #3498db;
    transform: translateX(-50%);
    border-radius: 5px;
}

.schedule-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin-top: 1rem;
}

.table-header {
    background: linear-gradient(135deg, #3498db, #2980b9);
    color: white;
    border-radius: 12px 12px 0 0;
}

.table-header th {
    padding: 1rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    text-align: center;
}

.time-slot {
    background-color: #f2f4f8;
    font-weight: 600;
    color: #2c3e50;
    text-align: center;
    width: 120px;
    border-right: 2px solid #e0e0e0;
}

.schedule-table td {
    padding: 0.75rem;
    border: 1px solid #eaeaea;
    height: 110px;
    vertical-align: top;
}

.class-card {
    border-radius: 10px;
    padding: 0.6rem 0.7rem;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    color: white;
    font-size: 0.85rem;
    transition: 0.3s ease;
}

.class-card:hover {
    transform: scale(1.02);
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
}

.morning-class {
    background: linear-gradient(135deg, #27ae60, #2ecc71);
}

.afternoon-class {
    background: linear-gradient(135deg, #f39c12, #e67e22);
}

.evening-class {
    background: linear-gradient(135deg, #8e44ad, #9b59b6);
}

.class-title {
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 0.3rem;
}

.class-details {
    font-size: 0.78rem;
    opacity: 0.9;
}

.empty-slot {
    color: #bdc3c7;
    font-style: italic;
    text-align: center;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Sidebar */
.min-vh-100 {
    min-height: 100vh;
}

.bg-light {
    background-color: #f7f7f7 !important;
}

.border-end {
    border-right: 1px solid #e0e0e0;
}
.container {
    margin-top: 5px; /* Thêm khoảng cách giữa phần tiêu đề và bảng lịch học */
}
/* Responsive */
@media (max-width: 992px) {
    .class-title {
        font-size: 0.85rem;
    }

    .class-details {
        font-size: 0.7rem;
    }

    .schedule-table td {
        height: 90px;
        padding: 0.4rem;
    }
}
</style>
@endsection
