<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordDealerType;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordDealerType $model
 */
class LandlordDealerTypeRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordDealerType::class;
    }
}
