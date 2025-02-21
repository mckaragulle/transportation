<?php

namespace App\Models\Landlord;

use App\Observers\Landlord\LandlordVehiclePropertyObserver;
use App\Traits\StrUuidTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

#[ObservedBy([LandlordVehiclePropertyObserver::class])]
class LandlordVehicleProperty extends Model
{
    use SoftDeletes, HasFactory, Sluggable, LogsActivity, StrUuidTrait;
    use UsesLandlordConnection;

    protected $connection = 'landlord';
    protected $keyType = 'string';
    protected $table = 'vehicle_properties';
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

    protected $fillable = ["vehicle_property_category_id", "vehicle_property_id", "name", "slug", "status"];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }

    /**
     * Get the prices for the type post.
     */
    public function vehicle_property_category(): BelongsTo
    {
        return $this->belongsTo(LandlordVehiclePropertyCategory::class);
    }

    /**
     * Get the prices for the type post.
     */
    public function vehicle_property(): BelongsTo
    {
        return $this->belongsTo(LandlordVehicleProperty::class);
    }

    /**
     * Get the prices for the type post.
     */
    public function vehicle_properties(): HasMany
    {
        return $this->hasMany(LandlordVehicleProperty::class);
    }

    protected static function booted(): void
    {
        static::created(fn (LandlordVehicleProperty $dish) => self::clearCache());
        static::updated(fn (LandlordVehicleProperty $dish) => self::clearCache());
        static::deleted(fn (LandlordVehicleProperty $dish) => self::clearCache());
    }

    private static function clearCache(): void
    {
        //Clear the PowerGrid cache tag
        Cache::tags([auth()->user()->id .'-powergrid-landlord-vehicle_properties-VehiclePropertyTable'])->flush();
    }
}
