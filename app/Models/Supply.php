<?php

namespace App\Models;

use App\Traits\HasNormalizedId;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    use HasNormalizedId;

    public const ID_PREFIX = "SUP";
    public const ID_NUMBER_LENGTH = 6;

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class, "ingredient_id", "id");
    }
}
