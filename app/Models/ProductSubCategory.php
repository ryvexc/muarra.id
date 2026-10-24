<?php

namespace App\Models;

use App\Traits\HasNormalizedId;
use Illuminate\Database\Eloquent\Model;

class ProductSubCategory extends Model
{
    use HasNormalizedId;

    public const ID_PREFIX = "SCT";
    public const ID_NUMBER_LENGTH = 4;

    protected $table = "product_subcategories";

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, "product_category_id", "id");
    }

    public function products()
    {
        return $this->hasMany(Product::class, "product_subcategory_id");
    }
}
