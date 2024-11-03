<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hgs extends Model
{
    use SoftDeletes, HasFactory, Sluggable, LogsActivity;

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

    protected $fillable = [
        "name",
        "slug",
        "number",
        "filename",
        "status",
        "buyed_at",
        "canceled_at",
    ];


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
        return $this->belongsTo(HgsTypeCategory::class, 'hgs_type_category_hgs_type_hgs');
    }

    /**
     * Get the prices for the type post.
     */
    public function hgs_type(): BelongsTo
    {
        return $this->belongsTo(HgsType::class, 'hgs_type_category_hgs_type_hgs');
    }

    public function hgs_type_categories(): BelongsToMany
    {
        return $this->belongsToMany(HgsTypeCategory::class, 'hgs_type_category_hgs_type_hgs');
    }

    public function hgs_types(): BelongsToMany
    {
        return $this->belongsToMany(HgsType::class, 'hgs_type_category_hgs_type_hgs');
    }
}
