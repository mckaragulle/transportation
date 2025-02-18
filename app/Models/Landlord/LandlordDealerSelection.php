<?php

namespace App\Models\Landlord;

use App\Traits\StrUuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class LandlordDealerSelection extends Model
{
    use HasFactory, LogsActivity, StrUuidTrait;
    use UsesLandlordConnection;

    public $incrementing = false;

    protected $connection = 'landlord';
    protected $keyType = 'string';
    protected $table = 'dealer_selections';

    protected $fillable = [
        "dealer_id",
        "dealer_address_id",
        "dealer_officer_id",
    ];

    /**
     * Get the Dealer.
     */
    public function dealer(): BelongsTo
    {
        return $this->belongsTo(LandlordDealer::class);
    }

    /**
     * Get the Address.
     */
    public function dealer_address(): BelongsTo
    {
        return $this->belongsTo(LandlordDealerAddress::class);
    }

    /**
     * Get the Officer.
     */
    public function dealer_officer(): BelongsTo
    {
        return $this->belongsTo(LandlordDealerOfficer::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }

    protected static function booted(): void
    {
        static::created(fn (LandlordDealerSelection $dish) => self::clearCache());
        static::updated(fn (LandlordDealerSelection $dish) => self::clearCache());
        static::deleted(fn (LandlordDealerSelection $dish) => self::clearCache());
    }

    private static function clearCache(): void
    {
        //Clear the PowerGrid cache tag
        Cache::tags([auth()->user()->id .'-powergrid-landlord-dealer_selection-DealerSelectionTable'])->flush();
    }
}
