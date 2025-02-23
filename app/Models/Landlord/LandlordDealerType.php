<?php

namespace App\Models\Landlord;

use App\Observers\Landlord\LandlordDealerTypeObserver;
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

#[ObservedBy([LandlordDealerTypeObserver::class])]
class LandlordDealerType extends Model
{
    use SoftDeletes, HasFactory, Sluggable, LogsActivity, StrUuidTrait;
    use UsesLandlordConnection;

    protected $connection = 'landlord';
    protected $keyType = 'string';
    protected $table = 'dealer_types';
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

    protected $fillable = ["dealer_type_category_id", "dealer_type_id", "name", "slug", "status"];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }

    /**
     * Get the prices for the type post.
     */
    public function dealer_type_category(): BelongsTo
    {
        return $this->belongsTo(LandlordDealerTypeCategory::class, 'dealer_type_category_id');
    }

    /**
     * Get the prices for the type post.
     */
    public function dealer_type(): BelongsTo
    {
        return $this->belongsTo(LandlordDealerType::class, 'dealer_type_id');
    }

    /**
     * Get the prices for the type post.
     */
    public function dealer_types(): HasMany
    {
        return $this->hasMany(LandlordDealerType::class, 'dealer_type_id');
    }

    protected static function booted(): void
    {
        static::created(fn (LandlordDealerType $dish) => self::clearCache());
        static::updated(fn (LandlordDealerType $dish) => self::clearCache());
        static::deleted(fn (LandlordDealerType $dish) => self::clearCache());
    }

    private static function clearCache(): void
    {
        //Clear the PowerGrid cache tag
        Cache::tags([auth()->user()->id .'-powergrid-landlord-dealer_type-DealerTypeTable'])->flush();
    }
}
