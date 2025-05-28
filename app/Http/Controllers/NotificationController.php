<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Hiển thị danh sách thông báo
    public function index()
    {
        $userId = Auth::id();
        $notifications = CustomNotification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('frontend.hoctap.notifications', compact('notifications'));
    }

    // Đánh dấu thông báo là đã đọc
    public function markAsRead($id)
    {
        $notification = CustomNotification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->update(['is_read' => true]);

        return redirect()->back();
    }
}
