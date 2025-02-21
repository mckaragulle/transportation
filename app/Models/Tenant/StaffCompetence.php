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

class StaffCompetence extends Model
{
    use SoftDeletes, HasFactory, LogsActivity, StrUuidTrait;
    use UsesTenantConnection;

    public $incrementing = false;

    protected $connection = 'tenant';
    protected $keyType = 'string';

    protected $fillable = [
        'staff_type_category_id',
        'staff_type_id',
        "staff_id",
        "expiry_date_at",
        "status",
    ];


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
        return $this->belongsTo(StaffTypeCategory::class, 'staff_type_category_id');
    }

    /**
     * Get the prices for the type post.
     */
    public function staff_type(): BelongsTo
    {
        return $this->belongsTo(StaffType::class, 'staff_type_id');
    }

    public function staff_types(): BelongsToMany
    {
        return $this->belongsToMany(StaffType::class, 'staff_competences', 'id');
    }
    public function staff_type_categories(): BelongsToMany
    {
        return $this->belongsToMany(StaffTypeCategory::class, 'staff_competences', 'id');
    }

    protected static function booted(): void
    {
        static::created(fn (StaffCompetence $dish) => self::clearCache());
        static::updated(fn (StaffCompetence $dish) => self::clearCache());
        static::deleted(fn (StaffCompetence $dish) => self::clearCache());
    }

    private static function clearCache(): void
    {
        //Clear the PowerGrid cache tag
        Cache::tags([auth()->user()->id .'-powergrid-tenant-staffs-StaffTable'])->flush();
    }
}
