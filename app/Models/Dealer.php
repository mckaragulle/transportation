<?php

namespace App\Models;

use App\Traits\StrUuidTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Dealer extends Authenticatable
{
    use SoftDeletes, HasFactory, Notifiable, HasRoles, Sluggable, LogsActivity, StrUuidTrait;
    use UsesTenantConnection;

    protected $guard_name = 'dealer';

    public $roleType = 'bayi';

    protected $keyType = 'string';
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
        return $this->belongsToMany(DealerType::class, 'dealer_type_category_dealer_type_dealer');
    }

}
