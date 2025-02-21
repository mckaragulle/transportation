<?php

namespace App\Models\Tenant;

use App\Traits\StrUuidTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class StaffTypeCategoryStaffTypeStaff extends Model
{
    use StrUuidTrait;
    use UsesTenantConnection;

    public $incrementing = false;

    protected $connection = 'tenant';
    protected $keyType = 'string';

    protected $table = 'staff_type_category_staff_type_staff';

    protected $fillable = ['staff_type_category_id', 'staff_type_id', 'staff_id'];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function staff_type(): BelongsTo
    {
        return $this->belongsTo(StaffType::class, 'staff_type_id');
    }

    public function staff_type_category(): BelongsTo
    {
        return $this->belongsTo(StaffTypeCategory::class, 'staff_type_category_id');
    }
}
