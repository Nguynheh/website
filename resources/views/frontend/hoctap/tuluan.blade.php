@extends('frontend.layouts.master')

@section('content')
    <div class="container essay-container">
        <h1 class="essay-title">Bài tự luận</h1>
<!-- Hiển thị thời gian còn lại -->
        <div class="time-remaining">
            <span class="info-label">Thời gian còn lại: </span>
            <span id="time-display">Đang tải...</span>
        </div>
        <div class="assignment-info">
            <span class="info-label">Nội dung bài:</span> {{ $assignment->quizTuLuan->title ?? 'Chưa có nội dung' }}
        </div>

        

        <form action="{{ route('frontend.hoctap.tuluan.submit', ['assignmentId' => $assignment->id]) }}" method="POST" class="essay-form" id="essay-form">
            @csrf

            <!-- Vòng lặp hiển thị câu hỏi -->
            @foreach($cauHoiList as $cauHoi)
                <div class="question-group">
                    <label for="answer_{{ $cauHoi->id }}" class="question-label">
                        {{ $cauHoi->content }}
                    </label>
                    <textarea name="answers[{{ $cauHoi->id }}]" 
                              id="answer_{{ $cauHoi->id }}" 
                              class="answer-textarea" 
                              rows="4" 
                              placeholder="Nhập câu trả lời của bạn..."></textarea>
                </div>
            @endforeach

            <button type="submit" class="submit-btn" id="submit-btn">Nộp bài</button>
        </form>
    </div>

    <script>
        let totalTimeInMinutes = {{ $assignment->quizTuLuan->time ?? 0 }};
        let timeRemaining = totalTimeInMinutes * 60;

        const timeDisplay = document.getElementById('time-display');
        const submitBtn = document.getElementById('submit-btn');
        const essayForm = document.getElementById('essay-form');

        function updateTime() {
            const minutes = Math.floor(timeRemaining / 60);
            const seconds = timeRemaining % 60;
            timeDisplay.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

            if (timeRemaining <= 0) {
                clearInterval(timerInterval);
                timeDisplay.textContent = "Thời gian đã hết!";
                submitBtn.disabled = true;
                essayForm.submit();
            }

            timeRemaining--;
        }

        const timerInterval = setInterval(updateTime, 1000);
    </script>
@endsection

<style>
/* Tổng thể */
.essay-container {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    max-width: 800px;
    margin: 2rem auto;
    padding: 2rem;
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

/* Tiêu đề */
.essay-title {
    color: #2c3e50;
    font-weight: 700;
    margin-bottom: 1.5rem;
    text-align: center;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #3498db;
    font-size: 1.75rem;
}

/* Thông tin bài */
.assignment-info {
    font-size: 1.1rem;
    color: #34495e;
    margin-bottom: 1.5rem;
    padding: 1rem;
    background-color: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #3498db;
}

.info-label {
    font-weight: 600;
    color: #2c3e50;
}

/* Thời gian */
.time-remaining {
    font-size: 1.2rem;
    color: #e74c3c;
    margin-bottom: 1.5rem;
    padding: 1rem;
    background-color: #f8fafc;
    border-radius: 8px;
    border-left: 4px solid #e74c3c;
    text-align: center;
    font-weight: 600;
}

/* Câu hỏi */
.question-group {
    margin-bottom: 1.5rem;
    padding: 1.5rem;
    background-color: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.question-label {
    display: block;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 1rem;
    font-size: 1.1rem;
}

/* Textarea */
.answer-textarea {
    width: 100%;
    padding: 1rem;
    border: 1px solid #dfe6e9;
    border-radius: 8px;
    font-size: 1rem;
    line-height: 1.6;
    background-color: #ffffff;
    min-height: 150px;
    transition: all 0.3s ease;
}

.answer-textarea:focus {
    border-color: #3498db;
    outline: none;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
}

/* Nút nộp bài */
.submit-btn {
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    color: white;
    border: none;
    border-radius: 6px;
    padding: 0.75rem 2rem;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 4px 10px rgba(46, 204, 113, 0.3);
    display: block;
    margin: 2rem auto 0;
}

.submit-btn:hover {
    background: linear-gradient(135deg, #27ae60, #2ecc71);
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(46, 204, 113, 0.4);
}

.submit-btn:disabled {
    background-color: #7f8c8d;
    cursor: not-allowed;
    box-shadow: none;
}

/* Responsive */
@media (max-width: 768px) {
    .essay-container {
        padding: 1.5rem;
        margin: 1.5rem;
    }

    .essay-title {
        font-size: 1.5rem;
    }

    .time-remaining {
        font-size: 1rem;
    }

    .question-group {
        padding: 1rem;
    }
}
</style>
