<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\VehicleModel;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var VehicleModel $model
 */
class VehicleModelRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return VehicleModel::class;
    }
}
