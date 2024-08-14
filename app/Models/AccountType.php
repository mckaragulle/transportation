<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class AccountType extends Model
{
    use HasFactory, Sluggable, LogsActivity;

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

    protected $fillable = ["account_type_category_id","account_type_id", "name", "slug", "status"];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }

    /**
     * Get the prices for the type post.
     */
    public function account_type_category(): BelongsTo
    {
        return $this->belongsTo(AccountTypeCategory::class);
    }

    /**
     * Get the prices for the type post.
     */
    public function vehicle_property(): BelongsTo
    {
        return $this->belongsTo(AccountType::class);
    }

    /**
     * Get the prices for the type post.
     */
    public function account_types(): HasMany
    {
        return $this->hasMany(AccountType::class);
    }
}
