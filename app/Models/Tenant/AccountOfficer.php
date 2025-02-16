<?php

namespace App\Models\Tenant;

use App\Models\Account;
use App\Models\Dealer;
use App\Traits\StrUuidTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class AccountOfficer extends Model
{
    use SoftDeletes, HasFactory, Sluggable, LogsActivity, StrUuidTrait;
    use UsesTenantConnection;

    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => ['name', 'surname']
            ]
        ];
    }

    protected $fillable = [
        "dealer_id",
        "account_id",
        "number",
        "name",
        "slug",
        "surname",
        "title",
        "phone1",
        "phone2",
        "email",
        "detail",
        "files",
        "status",
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'files' => AsArrayObject::class,
        ];
    }

    /**
     * Get the user's first name.
     */
    protected function files(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => json_decode($value),
        );
    }

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
