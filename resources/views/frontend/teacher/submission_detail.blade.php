@extends('frontend.layouts.master')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">📚 Chi tiết bài nộp</h4>

    

    <!-- Chi tiết bài nộp trắc nghiệm -->
    <h5>Câu hỏi Trắc nghiệm</h5>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Câu hỏi</th>
                <th>Đáp án</th>
                <th>Điểm</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tracnghiemQuestions as $question)
            <tr>
                <td>{{ $question->title }}</td>
                <td>{{ $tracnghiemAnswers[$question->id]->title ?? 'Chưa trả lời' }}</td>
                <td>{{ $tracnghiemAnswers[$question->id]->score ?? 'Chưa chấm' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Chi tiết bài nộp tự luận -->
    <h5>Câu hỏi Tự luận</h5>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Câu hỏi</th>
                <th>Đáp án</th>
                <th>Điểm</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tuluanQuestions as $question)
            <tr>
                <td>{{ $question->content }}</td>
                <td>{{ $tuluanAnswers[$question->id]->answer ?? 'Chưa trả lời' }}</td>
                <td>
                    <form action="{{ route('teacher.gradeTuluan', $tuluanAnswers[$question->id]->id) }}" method="POST">
                        @csrf
                        <input type="number" name="score" value="{{ $tuluanAnswers[$question->id]->score }}" />
                        <button type="submit" class="btn btn-primary">Chấm điểm</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
<style>
    /* Main container */
    .container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 1.5rem;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    /* Page title */
    h3 {
        color: #2d3748;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #4299e1;
        text-align: center;
        font-size: 1.5rem;
    }

    /* Score table */
    .table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    /* Table header */
    .table thead th {
        background: #2b6cb0;
        color: white;
        font-weight: 500;
        padding: 1rem;
        text-align: center;
        vertical-align: middle;
        position: sticky;
        top: 0;
    }

    /* Table rows */
    .table tbody tr {
        transition: all 0.2s ease;
    }

    /* Alternate row colors */
    .table tbody tr:nth-child(even) {
        background: #f7fafc;
    }

    /* Hover effect */
    .table tbody tr:hover {
        background: #ebf8ff;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    /* Table cells */
    .table td {
        padding: 1rem;
        border-top: 1px solid #e2e8f0;
        text-align: center;
        color: #4a5568;
    }

    /* Student name column */
    .table td:first-child {
        text-align: left;
        font-weight: 500;
        color: #2d3748;
    }

    /* Score styling */
    .table td:not(:first-child) {
        font-family: 'Courier New', monospace;
        font-weight: 500;
    }

    /* No score styling */
    .table td:empty,
    .table td[data-content="Chưa có điểm"] {
        color: #e53e3e;
        font-style: italic;
    }

    /* Has score styling */
    .table td:not(:first-child):not(:empty):not([data-content="Chưa có điểm"]) {
        color: #38a169;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .container {
            padding: 1rem;
            border-radius: 8px;
        }
        
        .table {
            display: block;
            overflow-x: auto;
        }
        
        .table thead th, 
        .table td {
            padding: 0.75rem;
            font-size: 0.9rem;
        }
        
        h3 {
            font-size: 1.3rem;
        }
    }

    /* Animation for table rows */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .table tbody tr {
        animation: fadeIn 0.3s ease forwards;
    }

    .table tbody tr:nth-child(1) { animation-delay: 0.1s; }
    .table tbody tr:nth-child(2) { animation-delay: 0.2s; }
    .table tbody tr:nth-child(3) { animation-delay: 0.3s; }
    /* Continue as needed... */
</style>