<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Licence;
use App\Models\Tenant\LicenceType;
use App\Models\Tenant\LicenceTypeCategory;
use App\Traits\StrUuidTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class LicenceTypeCategoryLicenceTypeLicence extends Model
{
    use StrUuidTrait;
    use UsesTenantConnection;

    protected $connection = 'tenant';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'licence_type_category_licence_type_licence';

    protected $fillable = ['licence_type_category_id', 'licence_type_id', 'licence_id'];

    public function licence(): BelongsTo
    {
        return $this->belongsTo(Licence::class, 'licence_id');
    }

    public function licence_type(): BelongsTo
    {
        return $this->belongsTo(LicenceType::class, 'licence_type_id');
    }

    public function licence_type_category(): BelongsTo
    {
        return $this->belongsTo(LicenceTypeCategory::class, 'licence_type_category_id');
    }
}
