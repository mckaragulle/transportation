<?php

namespace App\Models\Landlord;

use App\Models\Scopes\StatusScope;
use App\Models\Tenant\DealerTypeCategory;
use App\Traits\StrUuidTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class LandlordDealerType extends Model
{
    use SoftDeletes, HasFactory, Sluggable, LogsActivity, StrUuidTrait;
    use UsesLandlordConnection;

    protected $connection = 'landlord';
    protected $keyType = 'string';
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

    protected $fillable = ["dealer_type_category_id", "dealer_type_id", "name", "slug", "phone", "email", "address", "status"];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }

    /**
     * Get the prices for the type post.
     */
    public function dealer_type_category(): BelongsTo
    {
        return $this->belongsTo(DealerTypeCategory::class);
    }

    /**
     * Get the prices for the type post.
     */
    public function dealer_type(): BelongsTo
    {
        return $this->belongsTo(Tenant\DealerType::class);
    }

    /**
     * Get the prices for the type post.
     */
    public function dealer_types(): HasMany
    {
        return $this->hasMany(Tenant\DealerType::class);
    }
}
