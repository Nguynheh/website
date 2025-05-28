@extends('frontend.hoctap.index')

@section('hoctap_content')
<div class="container mt-4">
    <h2 class="mb-3">Làm Bài Tập</h2>

    <div class="card">
        <div class="card-body">
            <h4 class="card-title">{{ $baiTap->title }}</h4>
            <p><strong>Học Phần:</strong> {{ $baiTap->hocphan->title ?? 'N/A' }}</p>
            <p><strong>Thời Gian:</strong> {{ $baiTap->time }} phút</p>
            <p><strong>Ngày Bắt Đầu:</strong> {{ $baiTap->start_time ? date('d/m/Y H:i', strtotime($baiTap->start_time)) : 'Chưa đặt' }}</p>
            <p><strong>Ngày Kết Thúc:</strong> {{ $baiTap->end_time ? date('d/m/Y H:i', strtotime($baiTap->end_time)) : 'Chưa đặt' }}</p>
            <p><strong>Điểm Tổng:</strong> {{ $baiTap->total_points }}</p>
        </div>
    </div>

    <!-- Form làm bài -->
    <form action="{{ route('exercise.submit', $baiTap->id) }}" method="POST">
        @csrf
        <h3 class="mt-4">Danh sách câu hỏi</h3>

        @if(!empty($questions) && count($questions) > 0)
            <ul class="list-group">
                @foreach($questions as $index => $question)
                    <li class="list-group-item">
                        <strong>Câu {{ $index + 1 }}:</strong> {!! $question->content !!}

                        @if($baiTap instanceof \App\Modules\Exercise\Models\BoDeTracNghiem)
                            <!-- Câu hỏi trắc nghiệm -->
                            @if(!empty($question->answers) && is_iterable($question->answers))
                                <ul class="mt-2">
                                    @foreach($question->answers as $answer)
                                        <li>
                                            <input type="radio" name="answers[{{ $question->id }}]" value="{{ $answer->id }}">
                                            {{ $answer->content }}
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-danger">Không có đáp án.</p>
                            @endif
                        @else
                            <!-- Câu hỏi tự luận -->
                            <textarea name="answers[{{ $question->id }}]" class="form-control mt-2" rows="3" placeholder="Nhập câu trả lời"></textarea>
                        @endif
                    </li>
                @endforeach
            </ul>

            <!-- Nút nộp bài -->
            <button type="submit" class="btn btn-primary mt-3">Nộp bài</button>
        @else
            <p class="text-danger">Không có câu hỏi nào!</p>
        @endif
    </form>

    <!-- Nút quay lại -->
    <a href="{{ route('exercise.index') }}" class="btn btn-secondary mt-3">Quay lại</a>
</div>
@endsection
