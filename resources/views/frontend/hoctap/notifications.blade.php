@extends('frontend.layouts.master')

@section('content')
<div class="container mt-4">
    <h3>Thông báo của bạn</h3>

    @if($notifications->isEmpty())
        <div class="alert alert-info">Không có thông báo nào.</div>
    @else
        <ul class="list-group">
            @foreach($notifications as $notification)
                <li class="list-group-item d-flex justify-content-between align-items-center 
                    {{ $notification->is_read ? '' : 'list-group-item-warning' }}">
                    <div>
                        <strong>{{ $notification->title }}</strong><br>
                        <small>{{ $notification->message }}</small><br>
                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                    @if(!$notification->is_read)
                        <a href="{{ route('notifications.read', $notification->id) }}" class="btn btn-sm btn-primary">
                            Đánh dấu đã đọc
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
