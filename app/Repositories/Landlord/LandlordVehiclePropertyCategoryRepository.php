<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordVehiclePropertyCategory;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordVehiclePropertyCategory $model
 */
class LandlordVehiclePropertyCategoryRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordVehiclePropertyCategory::class;
    }
}
