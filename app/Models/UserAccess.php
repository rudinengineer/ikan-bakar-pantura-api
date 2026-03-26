<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAccess extends Model
{
    protected $fillable = [
        'role_id',
        'user_access_item_id',
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

    protected $casts = [
        'access_create' => 'boolean',
        'access_read' => 'boolean',
        'access_update' => 'boolean',
        'access_delete' => 'boolean',
        'access_menu' => 'boolean',
        'access_member' => 'boolean',
        'access_approve' => 'boolean',
    ];

    public function user_access_item()
    {
        return $this->belongsTo(UserAccessItem::class);
    }
}
