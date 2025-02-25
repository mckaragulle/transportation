<?php

namespace App\Models\Landlord;

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
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class LandlordBranchSector extends Model
{
    use SoftDeletes, HasFactory, Sluggable, LogsActivity, StrUuidTrait;
    use UsesLandlordConnection;

    protected $connection = 'landlord';
    protected $keyType = 'string';
    protected $table = 'branch_sectors';
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

    protected $fillable = ["branch_sector_id", "name", "slug", "status"];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }

    /**
     * Get the prices for the type post.
     */
    public function branch_sector(): BelongsTo
    {
        return $this->belongsTo(LandlordBranchSector::class);
    }

    /**
     * Get the prices for the type post.
     */
    public function branch_sectors(): HasMany
    {
        return $this->hasMany(LandlordBranchSector::class);
    }

    protected static function booted(): void
    {
        static::created(fn (LandlordBranchSector $dish) => self::clearCache());
        static::updated(fn (LandlordBranchSector $dish) => self::clearCache());
        static::deleted(fn (LandlordBranchSector $dish) => self::clearCache());
    }

    private static function clearCache(): void
    {
        //Clear the PowerGrid cache tag
        Cache::tags([auth()->user()->id .'-powergrid-landlord-branch_sectors-SectorTable'])->flush();
    }
}
