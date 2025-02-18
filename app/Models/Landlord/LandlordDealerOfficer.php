<?php

namespace App\Models\Landlord;

use App\Traits\StrUuidTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class LandlordDealerOfficer extends Model
{
    use SoftDeletes, HasFactory, Sluggable, LogsActivity, StrUuidTrait;
    use UsesLandlordConnection;

    public $incrementing = false;

    protected $connection = 'landlord';
    protected $keyType = 'string';
    protected $table = 'dealer_officers';

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['name', 'surname']
            ]
        ];
    }

    protected $fillable = [
        "dealer_id",
        "number",
        "name",
        "slug",
        "surname",
        "title",
        "phone1",
        "phone2",
        "email",
        "detail",
        "files",
        "status",
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'files' => AsArrayObject::class,
        ];
    }

    /**
     * Get the user's first name.
     */
    protected function files(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => json_decode($value),
        );
    }

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

    protected static function booted(): void
    {
        static::created(fn (LandlordDealerOfficer $dish) => self::clearCache());
        static::updated(fn (LandlordDealerOfficer $dish) => self::clearCache());
        static::deleted(fn (LandlordDealerOfficer $dish) => self::clearCache());
    }

    private static function clearCache(): void
    {
        //Clear the PowerGrid cache tag
        Cache::tags([auth()->user()->id .'-powergrid-landlord-dealer_officer-DealerOfficerTable'])->flush();
    }
}
