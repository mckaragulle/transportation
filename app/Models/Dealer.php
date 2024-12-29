<?php

namespace App\Models;

use App\Traits\StrUuidTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dealer extends Authenticatable
{
    use SoftDeletes, HasFactory, Notifiable, HasRoles, Sluggable, LogsActivity, StrUuidTrait;

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

}
