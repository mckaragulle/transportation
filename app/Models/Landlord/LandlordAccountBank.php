<?php

namespace App\Models\Landlord;

use App\Models\LandlordBank\LandlordBank;
use App\Traits\StrUuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class LandlordAccountBank extends Model
{
    use SoftDeletes, HasFactory, LogsActivity, StrUuidTrait;
    use UsesLandlordConnection;

    protected $connection = 'landlord';
    protected $keyType = 'string';
    protected $table = 'account_addresses';
    public $incrementing = false;

    protected $fillable = [
        "dealer_id",
        "account_id",
        "bank_id",
        "iban",
    ];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }

    /**
     * Get the Dealer.
     */
    public function dealer(): BelongsTo
    {
        return $this->belongsTo(LandlordDealer::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(LandlordAccount::class);
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(LandlordBank::class);
    }

    protected static function booted(): void
    {
        static::created(fn (LandlordAccountBank $dish) => self::clearCache());
        static::updated(fn (LandlordAccountBank $dish) => self::clearCache());
        static::deleted(fn (LandlordAccountBank $dish) => self::clearCache());
    }

    private static function clearCache(): void
    {
        //Clear the PowerGrid cache tag
        Cache::tags([auth()->user()->id .'-powergrid-landlord-account-bank-AccountBankTable'])->flush();
    }
}
