<?php

namespace App\Models;

use App\Traits\HasNormalizedId;
use Illuminate\Database\Eloquent\Model;

class InventoryHistory extends Model
{
    use HasNormalizedId;

    public const ID_PREFIX = "IVH";
    public const ID_NUMBER_LENGTH = 6;

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function createdByStaff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class);
    }
}
