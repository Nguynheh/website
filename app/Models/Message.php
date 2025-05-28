<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Teaching_2\Models\PhanCong;
class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'phancong_id',
        'user_id',
        'content',
    ];

    /**
     * Tin nhắn thuộc về một người dùng (người gửi).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Tin nhắn thuộc về một phân công lớp học phần.
     */
    public function phancong()
    {
        return $this->belongsTo(PhanCong::class, 'phancong_id');
    }
}
