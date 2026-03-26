<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $fillable = [
        'app_name',
        'left_footer_text',
        'right_footer_text',
        'login_footer_text',
        'created_by',
        'updated_by'
    ];
}
