<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait StrUuidTrait
{
    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
}
