<?php

namespace App\Models;

use App\Traits\HasNormalizedId;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasNormalizedId;

    public const ID_PREFIX = "STK";
    public const ID_NUMBER_LENGTH = 3;

    protected $appends = ['normalized_id'];

    public function getNormalizedIdAttribute(): string
    {
        return $this->normalizedId();
    }

    public function supplies()
    {
        return $this->hasMany(Supply::class, "ingredient_id", "id");
    }

    public function unit_of_measurement()
    {
        return $this->belongsTo(Unit::class, "unit_of_measurement_id");
    }

    public function getSupplyInAttribute(): int
    {
        return $this->supplies()
            ->whereNull("stock_report_id")
            ->sum("quantity"); // ganti "quantity" kalau nama kolom qty lu beda
    }
}
