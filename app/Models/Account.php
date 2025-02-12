<?php

namespace App\Models;

use App\Traits\StrUuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Account extends Model
{
    use SoftDeletes, HasFactory, LogsActivity, StrUuidTrait;
    use UsesTenantConnection;
    
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ["number", "name", "shortname", "phone", "email", "detail", "tax", "taxoffice", "status"];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }
    
    /**
     * Get the prices for the type post.
     */
    public function account_type_category(): BelongsTo
    {
        return $this->belongsTo(AccountTypeCategory::class, 'account_type_category_account_type_account');
    }

    /**
     * Get the prices for the type post.
     */
    public function account_type(): BelongsTo
    {
        return $this->belongsTo(AccountType::class, 'account_type_category_account_type_account');
    }

    public function account_type_categories(): BelongsToMany
    {
        return $this->belongsToMany(AccountTypeCategory::class, 'account_type_category_account_type_account');
    }

    public function account_types(): BelongsToMany
    {
        return $this->belongsToMany(AccountType::class, 'account_type_category_account_type_account');
    }
}
