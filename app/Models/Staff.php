<?php

namespace App\Models;

use App\Traits\HasNormalizedId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'email',
    'password',
    'tenant_id',
    'branch_id',
    'role_id',
])]
#[Hidden(['password', 'remember_token'])]
class Staff extends Authenticatable
{
    use HasNormalizedId, Notifiable;

    public const ID_PREFIX = "STF";
    public const ID_NUMBER_LENGTH = 4;

    protected $table = "staffs";

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
