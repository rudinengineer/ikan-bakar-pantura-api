<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = ['id'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function order_items()
    {
        return $this->hasMany(OrderItems::class);
    }
}
