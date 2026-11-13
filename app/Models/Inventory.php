<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['tenant_id', 'branch_id', 'ingredient_id', 'stock'])]
class Inventory extends Model
{
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function getSupplyInAttribute(): int
    {
        return $this->ingredient->supplies()
            ->where("branch_id", $this->branch_id)
            ->whereNull("stock_report_id")
            ->sum("quantity");
    }

    public function getOutgoingStockAttribute(): int
    {
        return $this->ingredient->inventoryHistory()
            ->where("branch_id", $this->branch_id)
            ->where("type", "out")
            ->where("reference_type", "order")
            ->whereNull("stock_report_id")
            ->sum("quantity"); // ganti "quantity" kalau nama kolom qty lu beda
    }
}
