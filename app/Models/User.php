<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Modules\Teaching_2\Models\HocPhan;
use App\Modules\Teaching_2\Models\ChuongTrinhDaoTao; 
use app\Modules\Teaching_1\Models\ClassRoom;
use App\Modules\Teaching_3\Models\PhancongGroup;
use App\Modules\Teaching_3\Models\Enrollment;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'global_id',
        'full_name',
        'username',
        'email',
        'password',
        'email_verified_at',
        'photo',
        'phone',
        'address',
        'description',
        'ship_id',
        'ugroup_id',
        'role',
        'budget',
        'totalpoint',
        'totalrevenue',
        'taxcode',
        'taxname',
        'taxaddress',
        'status',
    ];
    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public static function deleteUser($user_id){
        $user = User::find($user_id);
        if(auth()->user()->role =='admin')
        {
            $user->delete();
            return 1;
        }
        else{
            $user->status = "inactive";
            $user->save();
            return 0;
        }
            
        
    }
    public static function c_create($data)
    {
        
        $pro = User::create($data);
        $pro->code = "CUS" . sprintf('%09d',$pro->id);
        $pro->save();
       
        
       
        return $pro;
    }
    
    public function registeredCourses()
{
    return $this->belongsToMany(HocPhan::class, 'dang_ky_hoc_phan', 'user_id', 'hocphan_id')->withTimestamps();
}
public function chuongTrinh()
{
    return $this->belongsTo(ChuongTrinhDaoTao::class, 'chuongtrinh_id');
}
public function enrollments()
{
    return $this->hasMany(Enrollment::class);
}
public function teacher()
{
    return $this->hasOne(\App\Modules\Teaching_1\Models\Teacher::class, 'user_id');
}
public function student()
    {
        return $this->hasOne(\App\Modules\Teaching_1\Models\Student::class, 'user_id');
    }
}       



