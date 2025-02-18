<?php

namespace App\Models\Landlord;

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
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class LandlordHgsType extends Model
{
    use SoftDeletes, HasFactory, Sluggable, LogsActivity, StrUuidTrait;
    use UsesLandlordConnection;

    protected $connection = 'landlord';
    protected $keyType = 'string';
    protected $table = 'hgs_types';
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

    protected $fillable = ["hgs_type_category_id", "hgs_type_id", "name", "slug", "status"];


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
        return $this->belongsTo(LandlordHgsTypeCategory::class);
    }

    /**
     * Get the prices for the type post.
     */
    public function hgs_type(): BelongsTo
    {
        return $this->belongsTo(LandlordHgsType::class);
    }

    /**
     * Get the prices for the type post.
     */
    public function hgs_types(): HasMany
    {
        return $this->hasMany(LandlordHgsType::class);
    }

    public function hgs_type_categories(): BelongsToMany
    {
        return $this->belongsToMany(LandlordHgsTypeCategory::class, 'hgs_type_category_hgs_type_hgs');
    }
}
