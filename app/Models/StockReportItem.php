<?php

namespace App\Models;

use App\Traits\HasNormalizedId;
use App\Traits\HasNormalizedIdStockReport;
use Illuminate\Database\Eloquent\Model;

class StockReportItem extends Model
{
    use HasNormalizedIdStockReport;

    public const ID_PREFIX = "SRP";
    public const ID_NUMBER_LENGTH = 3;

    protected $appends = ['normalized_id'];

    public function stockReport()
    {
        return $this->belongsTo(StockReport::class);
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}
