<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Account;
use App\Models\Tenant\Sector;
use App\Traits\StrUuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class AccountSector extends Model
{
    use HasFactory, StrUuidTrait;
    use UsesTenantConnection;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        "account_id",
        "sector_id",
    ];

    /**
     * Get the prices for the type post.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the prices for the type post.
     */
    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }
}
