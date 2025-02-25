<?php

namespace App\Models\Tenant;

use App\Traits\StrUuidTrait;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class AccountTypeCategoryAccountTypeAccount extends Pivot
{
    use StrUuidTrait;
    use UsesTenantConnection;

    public $incrementing = false;

    protected $connection = 'tenant';
    protected $keyType = 'string';
    protected $table = 'account_type_category_account_type_account';

    protected $fillable = ['account_type_category_id', 'account_type_id', 'account_id'];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function account_type()
    {
        return $this->belongsTo(AccountType::class, 'account_type_id');
    }

    public function account_type_category()
    {
        return $this->belongsTo(AccountTypeCategory::class, 'account_type_category_id');
    }
}
