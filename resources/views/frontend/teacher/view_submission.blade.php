@extends('frontend.layouts.master')

@section('content')
<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-body">

            <h3 class="mb-4">Chi tiết bài làm của sinh viên</h3>

            <p><strong>Loại bài tập:</strong> 
                {{ $assignment->quiz_type == 'trac_nghiem' ? 'Trắc nghiệm' : 'Tự luận' }}
            </p>

            <h5 class="mt-4">Thông tin sinh viên:</h5>
            @if($submission->student && $submission->student->user)
                <p class="fs-5">{{ $submission->student->user->full_name }}</p>
            @endif

            <hr>

            <h5>Nội dung bài làm:</h5>

           @foreach ($questions as $question)
    @php
        $content = $question->content;
        if (is_array($content)) {
            $content = implode("\n", $content);
        }

        $studentAnswer = $answers[$question->id] ?? null;
        if (is_array($studentAnswer)) {
            $studentAnswer = implode(", ", $studentAnswer);
        }
    @endphp

    <div class="mb-4 p-3 border rounded bg-light">
        <p><strong>Câu hỏi {{ $question->id }}:</strong></p>
        <p>{!! nl2br(e($content)) !!}</p>

        <p><strong>Đáp án của sinh viên:</strong></p>
        <p class="text-primary fw-semibold">{!! nl2br(e($studentAnswer ?? 'Chưa trả lời')) !!}</p>
    </div>
@endforeach


            @if ($assignment->quiz_type != 'trac_nghiem')
                <hr>
                <h5 class="mb-3">Chấm điểm bài làm</h5>

                @if(session('message'))
                    <div class="alert alert-success">{{ session('message') }}</div>
                @endif

                <form action="{{ route('teacher.grade.tuluan', ['assignmentId' => $assignment->id, 'submissionId' => $submission->id]) }}" method="POST" class="row g-3 align-items-center">
                    @csrf
                    <div class="col-auto">
                        <label for="score" class="col-form-label">Điểm (0-10):</label>
                    </div>
                    <div class="col-auto">
                        <input type="number" class="form-control" id="score" name="score" min="0" max="100" value="{{ old('score', $submission->score ?? '') }}" required>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-success">Chấm điểm</button>
                    </div>
                </form>
            @endif

        </div>
    </div>
</div>
@endsection
