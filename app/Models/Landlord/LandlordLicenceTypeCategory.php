<?php

namespace App\Models\Landlord;

use App\Models\Tenant\Licence;
use App\Observers\LicenceTypeCategoryObserver;
use App\Traits\StrUuidTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Multitenancy\Contracts\IsTenant;
use Spatie\Multitenancy\Models\Concerns\ImplementsTenant;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

#[ObservedBy([LicenceTypeCategoryObserver::class])]
class LandlordLicenceTypeCategory extends Model implements IsTenant
{
    use SoftDeletes, HasFactory, Sluggable, LogsActivity, StrUuidTrait;
    use UsesLandlordConnection;
    use ImplementsTenant;

    /*protected $connection = 'landlord';*/
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['name', 'slug', 'status'];

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

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => 'boolean'
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }

    /**
     * Get the area's status
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => $value ? 1 : 0,
        );
    }

    public function licence_types(): HasMany
    {
        return $this->hasMany(LandlordLicenceType::class)->orderBy('updated_at', 'desc');
    }

    public function licences(): BelongsToMany
    {
        return $this->belongsToMany(
            Licence::class,
            'licence_type_category_licence_type_licence',
        );
    }
}
