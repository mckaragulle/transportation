<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordDealer;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * @var LandlordDealer $model
 */
class LandlordDealerRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordDealer::class;
    }
}
