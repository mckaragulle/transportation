<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordVehicleBrand;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordVehicleBrand $model
 */
class LandlordVehicleBrandRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordVehicleBrand::class;
    }
}
