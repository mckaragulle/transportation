<?php

namespace App\Models\Landlord;

use App\Observers\Landlord\LandlordLocalityObserver;
use App\Traits\StrUuidTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

#[ObservedBy([LandlordLocalityObserver::class])]
class LandlordLocality extends Model
{
    use SoftDeletes, HasFactory, Sluggable, LogsActivity, StrUuidTrait;
    use UsesLandlordConnection;

    protected $connection = 'landlord';
    protected $keyType = 'string';
    protected $table = 'localities';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'city_id',
        'district_id',
        'neighborhood_id',
        'name',
        'slug',
        'status',
    ];

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
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
        return $this->belongsTo(Neighborhood::class);
    }

    protected static function booted(): void
    {
        static::created(fn (LandlordLocality $dish) => self::clearCache());
        static::updated(fn (LandlordLocality $dish) => self::clearCache());
        static::deleted(fn (LandlordLocality $dish) => self::clearCache());
    }

    private static function clearCache(): void
    {
        Cache::tags(['powergrid-landlord-localities-LocalityTable'])->flush();
    }
}
