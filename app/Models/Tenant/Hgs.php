<?php

namespace App\Models\Tenant;

use App\Traits\StrUuidTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Hgs extends Model
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

    protected $fillable = [
        "name",
        "slug",
        "number",
        "filename",
        "status",
        "buyed_at",
        "canceled_at",
    ];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }

    /**
     * Get the prices for the type post.
     */
    public function hgs_type_category(): BelongsTo
    {
        return $this->belongsTo(HgsTypeCategory::class, 'hgs_type_category_hgs_type_hgs');
    }

    /**
     * Get the prices for the type post.
     */
    public function hgs_type(): BelongsTo
    {
        return $this->belongsTo(HgsType::class, 'hgs_type_category_hgs_type_hgs');
    }

    public function hgs_type_categories(): BelongsToMany
    {
        return $this->belongsToMany(HgsTypeCategory::class, 'hgs_type_category_hgs_type_hgs');
    }

    public function hgs_types(): BelongsToMany
    {
        return $this->belongsToMany(HgsType::class, 'hgs_type_category_hgs_type_hgs');
    }

    protected static function booted(): void
    {
        static::created(fn (Hgs $dish) => self::clearCache());
        static::updated(fn (Hgs $dish) => self::clearCache());
        static::deleted(fn (Hgs $dish) => self::clearCache());
    }

    private static function clearCache(): void
    {
        //Clear the PowerGrid cache tag
        Cache::tags([auth()->user()->id .'-powergrid-tenant-hgs-HgsTable'])->flush();
    }
}
