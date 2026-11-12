<?php

namespace App\Models;

use App\Traits\HasNormalizedId;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasNormalizedId;

    public const ID_PREFIX = "ORD";
    public const ID_NUMBER_LENGTH = 4;

    public $with = ["orderItems"];

    public function cashier()
    {
        return $this->belongsTo(Staff::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
