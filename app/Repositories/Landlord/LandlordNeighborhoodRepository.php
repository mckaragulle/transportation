<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordNeighborhood;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordNeighborhood $model
 */
class LandlordNeighborhoodRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordNeighborhood::class;
    }
}
