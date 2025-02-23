<?php

namespace App\Models\Landlord;

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
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;
use Spatie\Permission\Traits\HasRoles;

class LandlordDealer extends Authenticatable
{
    use SoftDeletes, HasFactory, Notifiable, HasRoles, Sluggable, LogsActivity, StrUuidTrait;
    use UsesLandlordConnection;

    public $incrementing = false;
    public $roleType = 'bayi';

    protected $guard_name = 'dealer';
    protected $connection = 'landlord';
    protected $keyType = 'string';
    protected $table = 'dealers';

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
        return $this->hasOne(LandlordDealerSelection::class, 'dealer_id');
    }

    /**
     * Get the prices for the type post.
     */
    public function dealer_type_category(): BelongsTo
    {
        return $this->belongsTo(LandlordDealerTypeCategory::class, 'dealer_type_category_dealer_type_dealer',
         'dealer_type_category_id', 'id');
    }

    /**
     * Get the prices for the type post.
     */
    public function dealer_type(): BelongsTo
    {
        return $this->belongsTo(LandlordDealerType::class, 'dealer_type_category_dealer_type_dealer',
            'dealer_type', 'id');
    }

    public function dealer_type_categories(): BelongsToMany
    {
        return $this->belongsToMany(LandlordDealerTypeCategory::class, 'dealer_type_category_dealer_type_dealer',
            'dealer_type_category_id', 'id');
    }

    public function dealer_types(): BelongsToMany
    {
        return $this->belongsToMany(LandlordDealerType::class, LandlordDealerTypeCategoryDealerTypeDealer::class,
            'dealer_type_id', 'id');

    }
}
