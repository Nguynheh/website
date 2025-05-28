<?php

namespace App\Modules\Group\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Teaching_2\Models\HocPhan;

class Group extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'status', 'private', 'category'
    ];
    public function groupMembers()
{
    return $this->hasMany(GroupMember::class, 'group_id');
}
public function hocphan()
{
    return $this->belongsTo(HocPhan::class, 'hocphan_id');
}

}
