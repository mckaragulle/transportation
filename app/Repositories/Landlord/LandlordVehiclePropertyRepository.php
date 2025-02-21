<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordVehicleProperty;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordVehicleProperty $model
 */
class LandlordVehiclePropertyRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordVehicleProperty::class;
    }
}
