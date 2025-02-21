<?php

namespace App\Models\Landlord;

use App\Traits\StrUuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class LandlordDealerLogo extends Model
{
    use HasFactory, LogsActivity, StrUuidTrait, SoftDeletes;
    use UsesLandlordConnection;

    public $incrementing = false;

    protected $connection = 'landlord';
    protected $keyType = 'string';
    protected $table = 'dealer_logos';

    protected $fillable = [
        "dealer_id",
        "title",
        "filename",
        "status"
    ];

    /**
     * Get the Dealer.
     */
    public function dealer(): BelongsTo
    {
        return $this->belongsTo(LandlordDealer::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }

    protected static function booted(): void
    {
        static::created(fn (LandlordDealerLogo $dish) => self::clearCache());
        static::updated(fn (LandlordDealerLogo $dish) => self::clearCache());
        static::deleted(fn (LandlordDealerLogo $dish) => self::clearCache());
    }

    private static function clearCache(): void
    {
        //Clear the PowerGrid cache tag
        Cache::tags([auth()->user()->id .'-powergrid-landlord-dealer_logo-DealerLogoTable'])->flush();
    }
}
