<?php

namespace App\Models\Tenant;

use App\Traits\StrUuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Branch extends Model
{
    use SoftDeletes, HasFactory, LogsActivity, StrUuidTrait;
    use UsesTenantConnection;

    public $incrementing = false;

    protected $connection = 'tenant';
    protected $keyType = 'string';
    protected $fillable = [
        "account_id",
        "city_id",
        "district_id",
        "name",
        "slug",
        "phone",
        "email",
        "status"
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }

    /**
     * Get the prices for the type post.
     */
    public function branch_type_category(): BelongsTo
    {
        return $this->belongsTo(BranchTypeCategory::class, 'branch_type_category_branch_type_branch');
    }

    /**
     * Get the prices for the type post.
     */
    public function branch_type(): BelongsTo
    {
        return $this->belongsTo(BranchType::class, 'branch_type_category_branch_type_branch');
    }

    public function branch_type_categories(): BelongsToMany
    {
        return $this->belongsToMany(BranchTypeCategory::class, 'branch_type_category_branch_type_branch');
    }

    public function branch_types(): BelongsToMany
    {
        return $this->belongsToMany(BranchType::class, 'branch_type_category_branch_type_branch');
    }

    protected static function booted(): void
    {
        static::created(fn (Branch $dish) => self::clearCache());
        static::updated(fn (Branch $dish) => self::clearCache());
        static::deleted(fn (Branch $dish) => self::clearCache());
    }

    private static function clearCache(): void
    {
        //Clear the PowerGrid cache tag
        Cache::tags([auth()->user()->id .'-powergrid-tenant-branch-BranchTable'])->flush();
    }
}
