<?php

namespace App\Repositories;

use App\Models\Tenant\VehicleProperty;
use Illuminate\Database\Eloquent\Model;

/**
 * @var VehicleProperty $model
 */
class VehiclePropertyRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return VehicleProperty::class;
    }
}
