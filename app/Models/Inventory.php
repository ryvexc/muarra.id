<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['tenant_id', 'branch_id', 'ingredient_id', 'stock'])]
class Inventory extends Model
{
    //
}
