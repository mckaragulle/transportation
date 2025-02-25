<?php

namespace App\Models\Tenant;

use App\Traits\StrUuidTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class BranchGroup extends Model
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

    protected $fillable = ["branch_group_id_id", "name", "slug", "status"];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }

    /**
     * Get the prices for the type post.
     */
    public function branch_group_id(): BelongsTo
    {
        return $this->belongsTo(BranchGroup::class);
    }


    /**
     * Get the prices for the type post.
     */
    public function branch_group_ids(): HasMany
    {
        return $this->hasMany(BranchGroup::class);
    }

    protected static function booted(): void
    {
        static::created(fn (BranchGroup $dish) => self::clearCache());
        static::updated(fn (BranchGroup $dish) => self::clearCache());
        static::deleted(fn (BranchGroup $dish) => self::clearCache());
    }

    private static function clearCache(): void
    {
        //Clear the PowerGrid cache tag
        Cache::tags([auth()->user()->id .'-powergrid-tenant-branch_groups-BranchGroupTable'])->flush();
    }
}
