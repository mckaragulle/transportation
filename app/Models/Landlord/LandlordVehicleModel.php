<?php

namespace App\Models\Landlord;

use App\Observers\Landlord\LandlordVehicleModelObserver;
use App\Traits\StrUuidTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

#[ObservedBy([LandlordVehicleModelObserver::class])]
class LandlordVehicleModel extends Model
{
    use SoftDeletes, HasFactory, Sluggable, LogsActivity, StrUuidTrait;
    use UsesLandlordConnection;

    use UsesLandlordConnection;

    protected $connection = 'landlord';
    protected $keyType = 'string';
    protected $table = 'vehicle_models';
    public $incrementing = false;

    protected $fillable = ["vehicle_brand_id", "vehicle_ticket_id", "name", "slug", "insurance_number", "status"];


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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }

    /**
     * Get the prices for the type post.
     */
    public function vehicle_brand(): BelongsTo
    {
        return $this->belongsTo(LandlordVehicleBrand::class);
    }

    /**
     * Get the prices for the type post.
     */
    public function vehicle_ticket(): BelongsTo
    {
        return $this->belongsTo(LandlordVehicleTicket::class);
    }
}
