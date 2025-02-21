<?php

namespace App\Models\Landlord;

use App\Traits\StrUuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class LandlordAccountSector extends Model
{
    use HasFactory, StrUuidTrait;
    use UsesLandlordConnection;

    protected $connection = 'landlord';
    protected $keyType = 'string';
    protected $table = 'account_sector';

    protected $fillable = [
        "account_id",
        "sector_id",
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
    public function sector(): BelongsTo
    {
        return $this->belongsTo(LandlordSector::class);
    }

    protected static function booted(): void
    {
        static::created(fn (LandlordAccountSector $dish) => self::clearCache());
        static::updated(fn (LandlordAccountSector $dish) => self::clearCache());
        static::deleted(fn (LandlordAccountSector $dish) => self::clearCache());
    }

    private static function clearCache(): void
    {
        //Clear the PowerGrid cache tag
        Cache::tags([auth()->user()->id .'-powergrid-landlord-account-sector-AccountSectorTable'])->flush();
    }
}
