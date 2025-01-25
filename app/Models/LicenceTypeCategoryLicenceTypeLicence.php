<?php

namespace App\Models;

use App\Traits\StrUuidTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class LicenceTypeCategoryLicenceTypeLicence extends Model
{
    use StrUuidTrait;
    use UsesTenantConnection;

    protected $connection = 'tenant';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'licence_type_category_licence_type_licence';

    // public function licence_type()
    // {
    //     return $this->belongsTo(LicenceType::class)
    //         ->on($this->getConnection()->getName());
    // }
    
    protected $fillable = ['licence_type_category_id', 'licence_type_id', 'licence_id'];

    public function licence()
    {
        return $this->belongsTo(Licence::class, 'licence_id');
    }

    public function licence_type()
    {
        return $this->belongsTo(LicenceType::class, 'licence_type_id');
    }

    public function licence_type_category()
    {
        return $this->belongsTo(LicenceTypeCategory::class, 'licence_type_category_id');
    }
}
