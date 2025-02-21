<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordVehicleModel;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordVehicleModel $model
 */
class LandlordVehicleModelRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordVehicleModel::class;
    }
}
