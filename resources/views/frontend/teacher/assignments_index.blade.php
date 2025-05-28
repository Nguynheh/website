@extends('frontend.layouts.master')

@section('content')
<div class="assignment-container">
    <div class="container">
        <div class="assignment-card">
            <h2 class="page-title">
                <i class="fas fa-tasks"></i> Danh sách bài tập đã giao
            </h2>

            @if($assignments->count() > 0)
                <table class="assignment-table">
                    <thead>
                        <tr>
                            <th>Tên bài tập</th>
                            <th>Loại</th>
                            <th>Hạn nộp</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignments as $assignment)
                            <tr class="assignment-row">
                                <td class="assignment-title">
                                    <strong>
                                        @if ($assignment->quiz_type === 'trac_nghiem')
                                            {{ $assignment->quizTracNghiem->title ?? 'N/A' }}
                                        @elseif ($assignment->quiz_type === 'tu_luan')
                                            {{ $assignment->quizTuLuan->title ?? 'N/A' }}
                                        @else
                                            N/A
                                        @endif
                                    </strong>
                                    <div class="class-name">{{ $assignment->class->name ?? '' }}</div>
                                </td>
                                <td class="assignment-type">
                                    <span class="badge {{ $assignment->quiz_type == 'trac_nghiem' ? 'badge-trac-nghiem' : 'badge-tu-luan' }}">
                                        {{ $assignment->quiz_type == 'trac_nghiem' ? 'Trắc nghiệm' : 'Tự luận' }}
                                    </span>
                                </td>
                                <td class="assignment-due">
                                    @php
                                        $dueDate = \Carbon\Carbon::parse($assignment->due_date);
                                        $today = \Carbon\Carbon::now();
                                        $isOverdue = $dueDate->lt($today);
                                        $isToday = $dueDate->isToday();
                                    @endphp
                                    <div class="due-date {{ $isOverdue ? 'overdue' : ($isToday ? 'today' : '') }}">
                                        {{ $dueDate->format('d/m/Y H:i') }}
                                        @if($isOverdue)
                                            <div class="status-label overdue-label">(Đã quá hạn)</div>
                                        @elseif($isToday)
                                            <div class="status-label today-label">(Hạn hôm nay)</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="assignment-actions">
                                    <a href="{{ route('teacher.assignment.results', $assignment->id) }}" class="action-btn btn-view">
                                        <i class="fas fa-eye mr-1"></i> Xem kết quả
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <i class="fas fa-book-open"></i>
                    <h4>Chưa có bài tập nào được giao</h4>
                    <p>Bạn chưa giao bài tập nào cho học sinh</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
<style>
    /* Cấu trúc tổng thể */
.assignment-container {
    padding: 30px 0;
    background-color: #f4f7fb;
}

.assignment-card {
    background-color: #fff;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}

.page-title {
    font-size: 24px;
    font-weight: 600;
    color: #333;
    display: flex;
    align-items: center;
}

.page-title i {
    margin-right: 10px;
}

.assignment-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.assignment-table th,
.assignment-table td {
    padding: 12px 15px;
    text-align: left;
    font-size: 14px;
}

.assignment-table th {
    background-color: #007bff;
    color: white;
    font-weight: 600;
}

.assignment-table td {
    background-color: #f9f9f9;
}

.assignment-row:nth-child(even) td {
    background-color: #f1f5f9;
}

.assignment-title strong {
    font-size: 16px;
    font-weight: 600;
    color: #333;
}

.class-name {
    color: #777;
    font-size: 12px;
    margin-top: 5px;
}

.assignment-type .badge {
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.badge-trac-nghiem {
    background-color: #28a745;
    color: white;
}

.badge-tu-luan {
    background-color: #007bff;
    color: white;
}

.assignment-due {
    font-size: 14px;
    font-weight: 500;
}

.due-date {
    color: #333;
}

.due-date.overdue {
    color: red;
}

.due-date.today {
    color: orange;
}

.status-label {
    font-size: 12px;
}

.status-label.overdue-label {
    color: red;
}

.status-label.today-label {
    color: orange;
}

.assignment-actions .action-btn {
    padding: 6px 12px;
    background-color: #007bff;
    color: white;
    border-radius: 5px;
    text-decoration: none;
    font-size: 14px;
    display: inline-block;
}

.assignment-actions .action-btn:hover {
    background-color: #0056b3;
}

.empty-state {
    text-align: center;
    margin-top: 50px;
}

.empty-state i {
    font-size: 40px;
    color: #ccc;
}

.empty-state h4 {
    font-size: 20px;
    color: #666;
}

.empty-state p {
    font-size: 14px;
    color: #888;
}

</style>