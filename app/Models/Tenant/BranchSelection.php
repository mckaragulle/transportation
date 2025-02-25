<?php

namespace App\Models\Tenant;

use App\Traits\StrUuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class BranchSelection extends Model
{
    use HasFactory, LogsActivity, StrUuidTrait;
    use UsesTenantConnection;

    public $incrementing = false;

    protected $connection = 'tenant';
    protected $keyType = 'string';
    protected $fillable = [
        "branch_id",
        "branch_address_id",
        "branch_officer_id",
    ];

    /**
     * Get the Branch.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the Address.
     */
    public function branch_address(): BelongsTo
    {
        return $this->belongsTo(BranchAddress::class);
    }

    /**
     * Get the Officer.
     */
    public function branch_officer(): BelongsTo
    {
        return $this->belongsTo(BranchOfficer::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }
}
