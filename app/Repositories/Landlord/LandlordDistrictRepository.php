<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordDistrict;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordDistrict $model
 */
class LandlordDistrictRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordDistrict::class;
    }
}
