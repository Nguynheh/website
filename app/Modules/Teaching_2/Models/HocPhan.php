<?php

namespace App\Modules\Teaching_2\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HocPhan extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'code',
        'content',
        'summary',
        'tinchi',
        'hinhthucthi'
    ];
    
    public function phancongs()
{
    return $this->hasMany(\App\Modules\Teaching_2\Models\PhanCong::class, 'hocphan_id');
}
}
