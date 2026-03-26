<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'level',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'level' => 'integer'
    ];
}
