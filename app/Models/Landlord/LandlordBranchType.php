<?php

namespace App\Models\Landlord;

use App\Observers\Landlord\LandlordBranchTypeObserver;
use App\Traits\StrUuidTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

#[ObservedBy([LandlordBranchTypeObserver::class])]
class LandlordBranchType extends Model
{
    use SoftDeletes, HasFactory, Sluggable, LogsActivity, StrUuidTrait;
    use UsesLandlordConnection;

    protected $connection = 'landlord';
    protected $keyType = 'string';
    protected $table = 'branch_types';
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

    protected $fillable = ["branch_type_category_id", "branch_type_id", "name", "slug", "phone", "email", "address", "status"];


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
        return $this->belongsTo(LandlordBranchTypeCategory::class, 'branch_type_category_id');
    }

    /**
     * Get the prices for the type post.
     */
    public function branch_type(): BelongsTo
    {
        return $this->belongsTo(LandlordBranchType::class, 'branch_type_id');
    }

    /**
     * Get the prices for the type post.
     */
    public function branch_types(): HasMany
    {
        return $this->hasMany(LandlordBranchType::class, 'branch_type_id');
    }

    protected static function booted(): void
    {
        static::created(fn (LandlordBranchType $dish) => self::clearCache());
        static::updated(fn (LandlordBranchType $dish) => self::clearCache());
        static::deleted(fn (LandlordBranchType $dish) => self::clearCache());
    }

    private static function clearCache(): void
    {
        //Clear the PowerGrid cache tag
        Cache::tags([auth()->user()->id .'-powergrid-landlord-branch-type-BranchTypeTable'])->flush();
    }
}
