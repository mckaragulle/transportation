<?php

namespace App\Models\Tenant;

use App\Traits\StrUuidTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class LicenceType extends Model
{
    use SoftDeletes, HasFactory, Sluggable, LogsActivity, StrUuidTrait;
    use UsesTenantConnection;

    public $incrementing = false;

    protected $connection = 'tenant';
    protected $keyType = 'string';

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

    protected $fillable = ["licence_type_category_id", "licence_type_id", "name", "slug", "phone", "email", "address", "status"];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }

    /**
     * Get the prices for the type post.
     */
    public function licence_type_category(): BelongsTo
    {
        return $this->belongsTo(LicenceTypeCategory::class, 'licence_type_category_id');
    }

    /**
     * Get the prices for the type post.
     */
    public function licence_type(): BelongsTo
    {
        return $this->belongsTo(LicenceType::class, 'licence_type_id');
    }

    /**
     * Get the prices for the type post.
     */
    public function licence_types(): HasMany
    {
        return $this->hasMany(LicenceType::class);
    }

    public function licences(): BelongsToMany
    {
        return $this->belongsToMany(
            Licence::class,
            'licence_type_category_licence_type_licence',
        );
    }
}
