<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PacketProducts extends Model
{
    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
