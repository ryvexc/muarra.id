<?php

namespace App\Models;

use App\Traits\HasNormalizedId;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasNormalizedId;

    public const ID_PREFIX = "PRD";
    public const ID_NUMBER_LENGTH = 4;

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, "product_category_id");
    }

    public function subcategory()
    {
        return $this->belongsTo(ProductSubCategory::class, "product_subcategory_id");
    }

    public function ingredients()
    {
        return $this->hasMany(ProductIngredient::class);
    }
}
