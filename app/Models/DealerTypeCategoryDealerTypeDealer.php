<?php

namespace App\Models;

use App\Traits\StrUuidTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class DealerTypeCategoryDealerTypeDealer extends Pivot
{
    use StrUuidTrait;
    use UsesTenantConnection;

    protected $connection = 'tenant';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $table = 'dealer_type_category_dealer_type_dealer';
    
    protected $fillable = ['dealer_type_category_id', 'dealer_type_id', 'dealer_id'];

    public function dealer()
    {
        return $this->belongsTo(Dealer::class, 'dealer_id');
    }

    public function dealer_type()
    {
        return $this->belongsTo(DealerType::class, 'dealer_type_id');
    }

    public function dealer_type_category()
    {
        return $this->belongsTo(DealerTypeCategory::class, 'dealer_type_category_id');
    }
}
