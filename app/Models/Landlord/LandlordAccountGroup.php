<?php

namespace App\Models;

use App\Models\Landlord\LandlordAccount;
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
    protected $table = 'account_groups';

    protected $fillable = [
        "account_id",
        "group_id",
    ];

    /**
     * Get the prices for the type post.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(LandlordAccount::class);
    }

    /**
     * Get the prices for the type post.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}