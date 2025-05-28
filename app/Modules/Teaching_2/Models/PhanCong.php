<?php
namespace App\Modules\Teaching_2\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Modules\Teaching_1\Models\Teacher;
use App\Modules\Teaching_2\Models\HocPhan;
use App\Modules\Teaching_2\Models\Hocky;
use App\Modules\Teaching_2\Models\Namhoc;
use App\Modules\Teaching_3\Models\Enrollment;
use App\Modules\Teaching_1\Models\ClassModel;

class PhanCong extends Model
{
    use HasFactory;

    protected $table = 'phancong'; // Tên bảng trong cơ sở dữ liệu
    protected $fillable = [
        'giangvien_id', 'hocphan_id', 'hocky_id', 'namhoc_id', 'ngayphancong', 'time_start', 'time_end','class_id'
    ];

    // Define relationships
    public function giangvien()
    {
        return $this->belongsTo(Teacher::class, 'giangvien_id'); // Liên kết đến model Teacher trong module Teaching_1
    }

    public function hocphan()
    {
        return $this->belongsTo(HocPhan::class, 'hocphan_id');
    }

    public function hocky()
    {
        return $this->belongsTo(Hocky::class, 'hocky_id');
    }
    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }
    public function namhoc()
    {
        return $this->belongsTo(Namhoc::class, 'namhoc_id');
    }
    public function enrollments()
{
    return $this->hasMany(Enrollment::class, 'phancong_id'); // Liên kết với bảng enrollments thông qua phancong_id
}

}

