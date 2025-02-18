<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\VehicleProperty;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

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
