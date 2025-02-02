<?php

namespace App\Models;

use App\Traits\StrUuidTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class VehicleProperty extends Model
{
    use SoftDeletes, HasFactory, Sluggable, LogsActivity, StrUuidTrait;
    use UsesLandlordConnection;

    protected $connection = 'landlord';
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
        return $this->belongsTo(VehiclePropertyCategory::class);
    }

    /**
     * Get the prices for the type post.
     */
    public function vehicle_property(): BelongsTo
    {
        return $this->belongsTo(VehicleProperty::class);
    }

    /**
     * Get the prices for the type post.
     */
    public function vehicle_properties(): HasMany
    {
        return $this->hasMany(VehicleProperty::class);
    }
}
