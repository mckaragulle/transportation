<?php

namespace App\Models\Tenant;

use App\Traits\StrUuidTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class StaffType extends Model
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

    protected $fillable = ["staff_type_category_id", "staff_type_id", "name", "slug", "phone", "email", "address", "status"];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }

    /**
     * Get the prices for the type post.
     */
    public function staff_type_category(): BelongsTo
    {
        return $this->belongsTo(StaffTypeCategory::class);
    }

    /**
     * Get the prices for the type post.
     */
    public function staff_type(): BelongsTo
    {
        return $this->belongsTo(StaffType::class);
    }

    /**
     * Get the prices for the type post.
     */
    public function staff_types(): HasMany
    {
        return $this->hasMany(StaffType::class);
    }
}
