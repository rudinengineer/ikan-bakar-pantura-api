<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAccessItem extends Model
{
    protected $fillable = [
        'access_name',
        'access_link',
        'access_create',
        'access_read',
        'access_update',
        'access_delete',
        'access_member',
        'access_menu',
        'access_approve',
        'created_by',
        'updated_by'
    ];

    public function user_access()
    {
        return $this->hasOne(UserAccess::class);
    }
}
