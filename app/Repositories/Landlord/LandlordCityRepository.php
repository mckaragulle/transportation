<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordCity;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordCity $model
 */
class LandlordCityRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordCity::class;
    }
}
