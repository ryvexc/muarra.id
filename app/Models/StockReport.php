<?php

namespace App\Models;

use App\Traits\HasNormalizedIdStockReport;
use Illuminate\Database\Eloquent\Model;

class StockReport extends Model
{
    use HasNormalizedIdStockReport;

    public const ID_PREFIX = "SR";
    public const ID_NUMBER_LENGTH = 3;

    protected $appends = ['normalized_id'];

    public function stockReportItem()
    {
        return $this->hasOne(StockReportItem::class);
    }
}
