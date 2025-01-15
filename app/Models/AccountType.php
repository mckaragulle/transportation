<?php

namespace App\Models;

use App\Models\Scopes\StatusScope;
use App\Traits\StrUuidTrait;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountType extends Model
{
    use SoftDeletes, HasFactory, Sluggable, LogsActivity, StrUuidTrait;

    protected $connection = 'pgsql_main';
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
                'source' => 'name'
            ]
        ];
    }

    protected $fillable = ["account_type_category_id", "account_type_id", "name", "slug", "phone", "email", "address", "status"];


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
        return $this->belongsTo(AccountTypeCategory::class);
    }

    /**
     * Get the prices for the type post.
     */
    public function account_type(): BelongsTo
    {
        return $this->belongsTo(AccountType::class);
    }

    /**
     * Get the prices for the type post.
     */
    public function account_types(): HasMany
    {
        return $this->hasMany(AccountType::class);
    }
}
