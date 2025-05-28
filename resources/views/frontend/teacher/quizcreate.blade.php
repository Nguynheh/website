@extends('frontend.layouts.master')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Tạo Bộ Đề</h1>
    <form action="{{ route('teacher.quiz.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Tiêu đề bộ đề</label>
            <input type="text" class="form-control" name="title" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Loại Bộ Đề</label>
            <select class="form-control" name="type" id="type" required>
                <option value="tracnghiem">Trắc nghiệm</option>
                <option value="tuluan">Tự luận</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Chọn học phần</label>
            <select class="form-control" name="hocphan_id" id="hocphan_id" required>
                @foreach($phancongs as $phancong)
                    <option value="{{ $phancong->hocphan->id }}">{{ $phancong->hocphan->title }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Thời gian làm bài (phút)</label>
            <input type="number" class="form-control" name="time" required min="1">
        </div>

        {{-- Phần TRẮC NGHIỆM --}}
        <div id="tracnghiem_section">
    <h5>Câu hỏi trắc nghiệm</h5>
    <div class="accordion" id="questionAccordion">
        @foreach($questions as $question)
            <div class="accordion-item mb-2 shadow-sm border rounded" data-hocphan="{{ $question->hocphan_id }}">
                <h2 class="accordion-header" id="heading{{ $question->id }}">
                    <button class="accordion-button collapsed d-flex align-items-center" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapse{{ $question->id }}" aria-expanded="false">
                        <div class="d-flex align-items-center">
                            <input type="checkbox" class="form-check-input me-2" name="selected_questions[]" value="{{ $question->id }}">
                            <span>{{ $question->content }}</span>
                        </div>
                    </button>
                </h2>
                <div id="collapse{{ $question->id }}" class="accordion-collapse collapse">
                    <div class="accordion-body">
                        <ul class="mb-2">
                            @foreach($question->answers as $answer)
                                <li>{{ chr(65 + $loop->index) }}. {{ $answer->content }}</li>
                            @endforeach
                        </ul>
                        <label>Điểm cho câu hỏi:</label>
                        <input type="number" name="points[{{ $question->id }}]" class="form-control w-25" step="0.5" min="0">
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>


        {{-- Phần TỰ LUẬN --}}
        <div id="tuluan_section" style="display: none;">
            <h5>Câu hỏi tự luận</h5>
            <div id="essay_container">
                <div class="mb-2">
                    <input type="text" name="essay_questions[]" class="form-control mb-1" placeholder="Nhập câu hỏi tự luận">
                    <input type="number" name="essay_points[]" class="form-control w-25" placeholder="Điểm" min="0">
                </div>
            </div>
            <button type="button" class="btn btn-secondary" onclick="addEssayQuestion()">Thêm câu hỏi tự luận</button>
        </div>

        {{-- Nút mở modal tạo câu hỏi mới --}}
        <button type="button" class="btn btn-outline-primary mt-3" id="openModalButton">Tạo câu hỏi mới</button>

        <button type="submit" class="btn btn-success mt-3">Lưu Bộ Đề</button>
    </form>
</div>

{{-- Modal Tạo Câu Hỏi --}}
<div class="modal fade" id="createQuestionModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('teacher.quiz.storeQuestion') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tạo Câu Hỏi Trắc Nghiệm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label>Câu hỏi</label>
                    <textarea name="new_question" class="form-control mb-2" required></textarea>

                    @for($i = 1; $i <= 4; $i++)
                        <label>Đáp án {{ chr(64 + $i) }}</label>
                        <input type="text" name="new_answer{{ $i }}" class="form-control mb-2" required>
                    @endfor

                    <label>Đáp án đúng</label>
                    <select name="new_correct_answer" class="form-control mb-2">
                        <option value="1">A</option>
                        <option value="2">B</option>
                        <option value="3">C</option>
                        <option value="4">D</option>
                    </select>

                    <label>Học phần</label>
                    <select name="hocphan_id" class="form-control">
                        @foreach($phancongs as $phancong)
                            <option value="{{ $phancong->hocphan->id }}">{{ $phancong->hocphan->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Lưu</button>
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Hủy</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const typeSelect = document.getElementById('type');
        const tracnghiemSection = document.getElementById('tracnghiem_section');
        const tuluanSection = document.getElementById('tuluan_section');
        const hocphanSelect = document.getElementById('hocphan_id');
        const accordionItems = document.querySelectorAll('.accordion-item');
        const modal = new bootstrap.Modal(document.getElementById('createQuestionModal'));
        const openModalButton = document.getElementById('openModalButton');

        function toggleSections() {
            if (typeSelect.value === 'tracnghiem') {
                tracnghiemSection.style.display = 'block';
                tuluanSection.style.display = 'none';
                openModalButton.style.display = 'inline-block';
            } else {
                tracnghiemSection.style.display = 'none';
                tuluanSection.style.display = 'block';
                openModalButton.style.display = 'none';
            }
        }

        function filterQuestions() {
            const selectedHocphan = hocphanSelect.value;
            accordionItems.forEach(item => {
                item.style.display = (item.dataset.hocphan === selectedHocphan) ? 'block' : 'none';
            });
        }

        window.addEssayQuestion = function () {
            const container = document.getElementById('essay_container');
            const div = document.createElement('div');
            div.className = 'mb-2';
            div.innerHTML = `
                <input type="text" name="essay_questions[]" class="form-control mb-1" placeholder="Nhập câu hỏi tự luận">
                <input type="number" name="essay_points[]" class="form-control w-25" placeholder="Điểm" min="0">
            `;
            container.appendChild(div);
        }

        typeSelect.addEventListener('change', toggleSections);
        hocphanSelect.addEventListener('change', filterQuestions);
        openModalButton.addEventListener('click', () => modal.show());

        // Initial load
        toggleSections();
        filterQuestions();
    });
</script>
@endsection
<style>
    /* Form chỉnh gọn gàng */
form .form-label {
    font-weight: 600;
}

form .form-control {
    border-radius: 8px;
    box-shadow: none;
    transition: 0.2s ease-in-out;
}

form .form-control:focus {
    border-color: #4A90E2;
    box-shadow: 0 0 0 0.15rem rgba(74, 144, 226, 0.25);
}

/* Accordion style */
.accordion-button {
    background-color: #f7f9fc;
    font-weight: 500;
}

.accordion-button:not(.collapsed) {
    background-color: #e2ecfb;
    color: #2c3e50;
}

.accordion-item {
    margin-bottom: 10px;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #dee2e6;
}

.accordion-body {
    background-color: #ffffff;
}

/* Section title */
h5 {
    margin-top: 20px;
    margin-bottom: 10px;
    font-weight: bold;
    color: #2c3e50;
}

/* Modal form */
.modal-content {
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.modal-header {
    background-color: #f0f4f8;
    border-bottom: 1px solid #dee2e6;
}

.modal-title {
    font-weight: bold;
}

.modal-footer {
    background-color: #f8f9fa;
    border-top: 1px solid #dee2e6;
}

/* Nút bấm */
.btn-outline-primary {
    border-radius: 8px;
}

.btn-success,
.btn-secondary,
.btn-primary {
    border-radius: 8px;
    font-weight: 500;
}

.btn:focus {
    box-shadow: none;
}

/* Trắc nghiệm & Tự luận section */
#tracnghiem_section,
#tuluan_section {
    padding: 15px;
    border: 1px solid #dee2e6;
    border-radius: 10px;
    background-color: #fafbfc;
    margin-bottom: 20px;
}

/* Tự luận */
#essay_container .form-control {
    display: inline-block;
    width: 80%;
    margin-right: 10px;
}

</style>