<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Packet extends Model
{
    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function packet_products()
    {
        return $this->hasMany(PacketProducts::class);
    }
}
