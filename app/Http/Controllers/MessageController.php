<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Modules\Teaching_2\Models\PhanCong;
class MessageController extends Controller
{
    // Hiển thị giao diện chat của 1 lớp học phần theo phân công
    public function index($phancong_id)
    {
        $phancong = PhanCong::findOrFail($phancong_id);
        $messages = Message::where('phancong_id', $phancong_id)
                            ->with('user') // để lấy thông tin người gửi
                            ->orderBy('created_at', 'asc')
                            ->get();

        return view('frontend.hoctap.chat', compact('messages', 'phancong'));
    }

    // Gửi tin nhắn mới
    public function store(Request $request, $phancong_id)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        Message::create([
            'phancong_id' => $phancong_id,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return redirect()->route('frontend.hoctap.chat', $phancong_id)->with('success', 'Đã gửi tin nhắn');
    }
}