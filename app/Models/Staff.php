<?php

namespace App\Models;

use App\Traits\HasNormalizedId;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasNormalizedId;

    public const ID_PREFIX = "STF";
    public const ID_NUMBER_LENGTH = 4;

    protected $table = "staffs";

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
