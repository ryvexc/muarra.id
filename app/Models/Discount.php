<?php

namespace App\Models;

use App\Traits\HasNormalizedId;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasNormalizedId;

    public const ID_PREFIX = "DSC";
    public const ID_NUMBER_LENGTH = 4;
}
