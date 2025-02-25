<?php

namespace App\Models\Tenant;

use App\Traits\StrUuidTrait;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class BranchTypeCategoryBranchTypeBranch extends Pivot
{
    use StrUuidTrait;
    use UsesTenantConnection;

    public $incrementing = false;

    protected $connection = 'tenant';
    protected $keyType = 'string';
    protected $table = 'branch_type_category_branch_type_branch';

    protected $fillable = ['branch_type_category_id', 'branch_type_id', 'branch_id'];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function branch_type()
    {
        return $this->belongsTo(BranchType::class, 'branch_type_id');
    }

    public function branch_type_category()
    {
        return $this->belongsTo(BranchTypeCategory::class, 'branch_type_category_id');
    }
}
