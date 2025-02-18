<?php

namespace App\Models\Landlord;

use App\Traits\StrUuidTrait;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class LandlordDealerTypeCategoryDealerTypeDealer extends Pivot
{
    use StrUuidTrait;
    use UsesLandlordConnection;

    public $incrementing = false;

    protected $connection = 'landlord';
    protected $keyType = 'string';
    protected $table = 'dealer_type_category_dealer_type_dealer';
    protected $fillable = ['dealer_type_category_id', 'dealer_type_id', 'dealer_id'];

    public function dealer()
    {
        return $this->belongsTo(LandlordDealer::class, 'dealer_id');
    }

    public function dealer_type()
    {
        return $this->belongsTo(LandlordDealerType::class, 'dealer_type_id');
    }

    public function dealer_type_category()
    {
        return $this->belongsTo(LandlordDealerTypeCategory::class, 'dealer_type_category_id');
    }
}
