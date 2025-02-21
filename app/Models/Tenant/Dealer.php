<?php

namespace App\Models\Tenant;

use App\Traits\StrUuidTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use Spatie\Permission\Traits\HasRoles;

class Dealer extends Authenticatable
{
    use SoftDeletes, HasFactory, Notifiable, HasRoles, Sluggable, LogsActivity, StrUuidTrait;
    use UsesTenantConnection;

    public $roleType = 'bayi';
    public $incrementing = false;

    protected $connection = 'tenant';
    protected $keyType = 'string';
    protected $guard_name = 'dealer';


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
        'name',
        'slug',
        'number',
        'shortname',
        'phone',
        'tax',
        'taxoffice',
        'detail',
        'email',
        'password',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => 'boolean'
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'slug',
                'number',
                'shortname',
                'phone',
                'tax',
                'taxoffice',
                'detail',
                'email',
                'password',
                'status'
            ]);
    }

    /**
     * Get the user's status
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => $value ? 1 : 0,
        );
    }

    /**
     * Get the Dealer.
     */
    public function dealer_selection(): HasOne
    {
        return $this->hasOne(DealerSelection::class, 'dealer_id');
    }

    /**
     * Get the prices for the type post.
     */
    public function dealer_type_category(): BelongsTo
    {
        return $this->belongsTo(DealerTypeCategory::class, 'dealer_type_category_dealer_type_dealer');
    }

    /**
     * Get the prices for the type post.
     */
    public function dealer_type(): BelongsTo
    {
        return $this->belongsTo(DealerType::class, 'dealer_type_category_dealer_type_dealer');
    }

    public function dealer_type_categories(): BelongsToMany
    {
        return $this->belongsToMany(DealerTypeCategory::class, 'dealer_type_category_dealer_type_dealer');
    }

    public function dealer_types(): BelongsToMany
    {
        return $this->belongsToMany(DealerType::class, DealerTypeCategoryDealerTypeDealer::class);
    }

    protected static function booted(): void
    {
        static::created(fn (Dealer $dish) => self::clearCache());
        static::updated(fn (Dealer $dish) => self::clearCache());
        static::deleted(fn (Dealer $dish) => self::clearCache());
    }

    private static function clearCache(): void
    {
        /*Cache::tags([auth()->user()->id .'-powergrid-tenant-dealer-DealerTable'])->flush();*/
    }
}
