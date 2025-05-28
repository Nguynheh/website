@extends('frontend.layouts.master')
@section('content')

<style>
/* Tổng thể */
.quiz-container {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    max-width: 800px;
    margin: 2rem auto;
    padding: 2rem;
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

/* Tiêu đề quiz */
.quiz-title {
    color: #2c3e50;
    font-weight: 700;
    margin-bottom: 1.5rem;
    text-align: center;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #3498db;
}

/* Thời gian còn lại */
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

.time-remaining span {
    font-weight: 600;
}

/* Thông báo */
.quiz-alert {
    padding: 1rem;
    border-radius: 6px;
    margin-bottom: 1.5rem;
    font-weight: 500;
}

.alert-warning {
    background-color: #fff3cd;
    color: #856404;
    border-left: 4px solid #ffc107;
}

/* Card câu hỏi */
.question-card {
    border: none;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.question-header {
    background: linear-gradient(135deg, #3498db, #2980b9);
    color: white;
    padding: 1rem 1.5rem;
}

.question-content {
    font-weight: 500;
    margin: 0;
}

.question-body {
    padding: 1.5rem;
}

/* Đáp án */
.answer-option {
    margin-bottom: 0.75rem;
    padding: 0.75rem;
    border-radius: 6px;
    background-color: #f8f9fa;
    transition: background-color 0.2s;
}

.answer-option:hover {
    background-color: #e9f5ff;
}

.form-check-input {
    margin-top: 0.3rem;
}

.form-check-label {
    margin-left: 0.5rem;
    cursor: pointer;
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

/* Thông báo không có câu hỏi */
.no-questions {
    color: #7f8c8d;
    font-style: italic;
    text-align: center;
    padding: 2rem;
    background-color: #f8f9fa;
    border-radius: 8px;
}

/* Responsive */
@media (max-width: 768px) {
    .quiz-container {
        padding: 1.5rem;
        margin: 1.5rem;
    }

    .quiz-title {
        font-size: 1.5rem;
    }

    .time-remaining {
        font-size: 1rem;
    }

    .question-card {
        padding: 1rem;
    }
}
</style>

<div class="container quiz-container">
    <h3 class="quiz-title">{{ $quiz->title }}</h3>

    <!-- Thời gian còn lại -->
    <div class="time-remaining">
        <span id="time-left">Thời gian còn lại: {{ $quiz->time }} phút</span>
    </div>

    <!-- Thông báo -->
    @if(session('message'))
        <div class="quiz-alert alert-warning">
            {{ session('message') }}
        </div>
    @endif

    <form action="{{ route('frontend.hoctap.quiz.submit', ['quizId' => $quiz->id, 'assignmentId' => $assignmentId]) }}" method="POST">
        @csrf

        @if($questions && $questions->isNotEmpty())
            @foreach($questions as $index => $question)
                <div class="card question-card">
                    <div class="card-header question-header">
                        <strong>Câu {{ $index + 1 }}:</strong>
                        <span class="question-content">{{ $question->content }}</span>
                    </div>
                    <div class="card-body question-body">
                        @if($question->answers && $question->answers->isNotEmpty())
                            <div class="form-group">
                                @foreach($question->answers as $answer)
                                    <div class="form-check answer-option">
                                        <input 
                                            class="form-check-input" 
                                            type="radio" 
                                            name="answers[{{ $question->id }}]" 
                                            value="{{ $answer->id }}" 
                                            id="answer_{{ $answer->id }}">
                                        <label class="form-check-label" for="answer_{{ $answer->id }}">
                                            {{ $answer->content }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p>Không có đáp án cho câu hỏi này.</p>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <p class="no-questions">Không có câu hỏi để hiển thị.</p>
        @endif

        <div class="text-center">
            <button type="submit" class="submit-btn mt-4">Nộp bài</button>
        </div>
    </form>
</div>

<!-- JavaScript đếm ngược thời gian -->
<script>
    let timeLeft = {{ $quiz->time * 60 }}; // tính theo giây

    function updateTime() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        document.getElementById("time-left").innerText = `Thời gian còn lại: ${minutes} phút ${seconds < 10 ? '0' : ''}${seconds} giây`;

        if (timeLeft <= 0) {
            document.querySelector(".submit-btn").disabled = true;
            document.getElementById("time-left").innerText = "Thời gian đã hết!";
        } else {
            timeLeft--;
        }
    }

    setInterval(updateTime, 1000);
</script>

@endsection
