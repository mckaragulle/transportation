<?php

namespace App\Models;

use App\Traits\StrUuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Licence extends Model
{
    use SoftDeletes, HasFactory, LogsActivity, StrUuidTrait;
    use UsesTenantConnection;


    public $incrementing = false;
    protected $keyType = 'string';
    protected $connection = 'tenant';

    protected $fillable = ["number", "filename", "detail", "status", "started_at", "finished_at"];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }

    /**
     * Get the prices for the type post.
     */
    public function licence_type_category(): BelongsTo
    {
        return $this->belongsTo(LicenceTypeCategory::class, 'licence_type_category_id');
    }

    /**
     * Get the prices for the type post.
     */
    public function licence_type(): BelongsTo
    {
        return $this->belongsTo(LicenceType::class, 'licence_type_id');
    }

    public function licence_types(): BelongsToMany
    {
        return $this->belongsToMany(LicenceType::class, 'licence_type_category_licence_type_licence');
    }
    public function licence_type_categories(): BelongsToMany
    {
        return $this->belongsToMany(LicenceTypeCategory::class, 'licence_type_category_licence_type_licence');
    }
}
