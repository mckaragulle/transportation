<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class VehicleModel extends Model
{
    use HasFactory, Sluggable, LogsActivity;


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
        return $this->belongsTo(VehicleBrand::class);
    }

    /**
     * Get the prices for the type post.
     */
    public function vehicle_ticket(): BelongsTo
    {
        return $this->belongsTo(VehicleTicket::class);
    }
}
