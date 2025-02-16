<?php

namespace App\Models\Tenant;

use App\Models\Tenant\LicenceType;
use App\Models\Tenant\LicenceTypeCategory;
use App\Traits\StrUuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Licence extends Model
{
    use SoftDeletes, HasFactory, LogsActivity, StrUuidTrait;
    use UsesTenantConnection;

    protected $connection = 'tenant';
    protected $keyType = 'string';
    public $incrementing = false;

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
