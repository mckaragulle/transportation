<?php

namespace App\Models\Landlord;

use App\Services\Landlord\LandlordAccountService;
use App\Traits\StrUuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class LandlordAccountGroup extends Model
{
    use HasFactory, StrUuidTrait;
    use UsesLandlordConnection;

    protected $connection = 'landlord';
    protected $keyType = 'string';
    protected $table = 'account_group';

    protected $fillable = [
        "account_id",
        "group_id",
    ];

    /**
     * Get the prices for the type post.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(LandlordAccountService::class);
    }

    /**
     * Get the prices for the type post.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(LandlordGroup::class);
    }
}
