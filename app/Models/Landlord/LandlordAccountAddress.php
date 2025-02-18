<?php

namespace App\Models\Landlord;

use App\Models\City;
use App\Models\District;
use App\Models\Tenant\Dealer;
use App\Models\Tenant\Neighborhood;
use App\Traits\StrUuidTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class LandlordAccountAddress extends Model
{
    use SoftDeletes, HasFactory, Sluggable, LogsActivity, StrUuidTrait;
    use UsesLandlordConnection;

    protected $connection = 'landlord';
    protected $keyType = 'string';
    protected $table = 'account_addresses';
    public $incrementing = false;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    protected $fillable = [
        "dealer_id",
        "account_id",
        "city_id",
        "district_id",
        "neighborhood_id",
        "locality_id",
        "name",
        "slug",
        "address1",
        "address2",
        "phone1",
        "phone2",
        "email",
        "detail",
        "status",
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
        return $this->belongsTo(Dealer::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(LandlordAccount::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(LandlordCity::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(LandlordDistrict::class);
    }

    public function neighborhood(): BelongsTo
    {
        return $this->belongsTo(LandlordNeighborhood::class);
    }

    public function locality(): BelongsTo
    {
        return $this->belongsTo(LandlordLocality::class);
    }

    protected static function booted(): void
    {
        static::created(fn (LandlordAccountAddress $dish) => self::clearCache());
        static::updated(fn (LandlordAccountAddress $dish) => self::clearCache());
        static::deleted(fn (LandlordAccountAddress $dish) => self::clearCache());
    }

    private static function clearCache(): void
    {
        //Clear the PowerGrid cache tag
        Cache::tags([auth()->user()->id .'-powergrid-landlord-account_addresses-AccountAddressTable'])->flush();
    }
}
