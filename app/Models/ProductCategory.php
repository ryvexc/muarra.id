<?php

namespace App\Models;

use App\Traits\HasNormalizedId;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasNormalizedId;

    public const ID_PREFIX = "PCT";
    public const ID_NUMBER_LENGTH = 4;

    public function subCategories()
    {
        return $this->hasMany(ProductSubCategory::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
