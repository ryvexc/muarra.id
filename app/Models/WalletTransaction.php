<?php

namespace App\Models;

use App\Traits\HasNormalizedIdTransaction;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasNormalizedIdTransaction;

    public const ID_PREFIX = "TRX";

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
