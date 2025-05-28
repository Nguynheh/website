@extends('frontend.layouts.master')

@section('content')
<div class="container py-4">
    <h5 class="mb-3">
        💬 Chat - {{ $phancong->hocphan->class_name ?? '' }} / {{ $phancong->hocphan->ten_hoc_phan ?? '' }}
    </h5>

    <div class="card mb-4">
        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
            @foreach ($messages as $message)
                <div class="d-flex align-items-start mb-3">
                    <div>
                        @if($message->user && $message->user->photo)
                            <img src="{{ asset($message->user->photo) }}" alt="avatar" 
                                 class="rounded-circle" 
                                 style="width: 40px; height: 40px; object-fit: cover; margin-right: 10px;">
                        @else
                            <div class="bg-secondary rounded-circle text-white d-flex justify-content-center align-items-center" 
                                 style="width: 40px; height: 40px; margin-right: 10px;">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <strong>{{ $message->user->full_name ?? 'Ẩn danh' }}</strong>:<br>
                        <span>{{ $message->content }}</span>
                        <div class="text-muted small">{{ $message->created_at->format('H:i d/m/Y') }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <form action="{{ route('frontend.hoctap.chat.store', $phancong->id) }}" method="POST">
        @csrf
        <div class="input-group">
            <input type="text" name="content" class="form-control" placeholder="Nhập nội dung..." required>
            <button class="btn btn-primary" type="submit">Gửi</button>
        </div>
    </form>

</div>
@endsection
