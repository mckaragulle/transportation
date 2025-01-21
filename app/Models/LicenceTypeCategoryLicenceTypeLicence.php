<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LicenceTypeCategoryLicenceTypeLicence extends Model
{
    protected $fillable = ['licence_type_category_id', 'licence_type_id', 'licence_id'];

    public function licence_type_categories(): HasMany
    {
        return $this->hasMany(LicenceTypeCategory::class);
    }
    public function licence_types(): HasMany
    {
        return $this->hasMany(LicenceType::class);
    }

    public function licences(): HasMany
    {
        return $this->hasMany(Licence::class);
    }
}
