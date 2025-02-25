<?php

namespace App\Models\Tenant;

use App\Traits\StrUuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class BranchFile extends Model
{
    use SoftDeletes, HasFactory, LogsActivity, StrUuidTrait;
    use UsesTenantConnection;

    public $incrementing = false;

    protected $connection = 'tenant';
    protected $keyType = 'string';

    protected $fillable = [
        "branch_id",
        "title",
        "filename",
        "status",
    ];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }

    /**
     * Get the Branch.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    protected static function booted(): void
    {
        static::created(fn (BranchFile $dish) => self::clearCache());
        static::updated(fn (BranchFile $dish) => self::clearCache());
        static::deleted(fn (BranchFile $dish) => self::clearCache());
    }

    private static function clearCache(): void
    {
        //Clear the PowerGrid cache tag
        Cache::tags([auth()->user()->id .'-powergrid-tenant-branch_files-BranchFileTable'])->flush();
    }
}
