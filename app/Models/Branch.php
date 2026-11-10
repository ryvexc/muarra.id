<?php

namespace App\Models;

use App\Traits\HasNormalizedId;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasNormalizedId;

    public const ID_PREFIX = "BRC";
    public const ID_NUMBER_LENGTH = 4;

    public function manager()
    {
        return $this->belongsTo(Staff::class, "manager_id", "id");
    }
}
