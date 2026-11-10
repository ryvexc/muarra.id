<?php

namespace App\Models;

use App\Traits\HasNormalizedId;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasNormalizedId;

    public const ID_PREFIX = "TNT";
    public const ID_NUMBER_LENGTH = 4;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->hasMany(Branch::class);
    }
}
