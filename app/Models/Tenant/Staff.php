<?php

namespace App\Models\Tenant;

use App\Traits\StrUuidTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Staff extends Model
{
    use SoftDeletes, HasFactory, Sluggable, LogsActivity, StrUuidTrait;
    use UsesTenantConnection;

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
                'source' => ['name', 'surname']
            ]
        ];
    }

    protected $fillable = [
        "id_number",
        "name",
        "slug",
        "surname",
        "phone1",
        "phone2",
        "email",
        "detail",
        "filename",
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
        return $this->belongsTo(StaffType::class, 'staff_type_category_id');
    }

    public function staff_type_categories(): BelongsToMany
    {
        return $this->belongsToMany(StaffTypeCategory::class, 'staff_type_category_staff_type_staff');
    }

    public function staff_types(): BelongsToMany
    {
        return $this->belongsToMany(StaffType::class, 'staff_type_category_staff_type_staff');
    }
}
