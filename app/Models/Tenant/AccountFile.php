<?php

namespace App\Models\Tenant;

use App\Models\Dealer;
use App\Traits\StrUuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class AccountFile extends Model
{
    use SoftDeletes, HasFactory, LogsActivity, StrUuidTrait;
    use UsesTenantConnection;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        "dealer_id",
        "account_id",
        "title",
        "filename",
        "status",
    ];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }

    /**
     * Get the Dealer.
     */
    public function dealer(): BelongsTo
    {
        return $this->belongsTo(Dealer::class);
    }

    /**
     * Get the prices for the type post.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
