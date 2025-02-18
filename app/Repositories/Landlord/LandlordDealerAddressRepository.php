<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordDealerAddress;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordDealerAddress $model
 */
class LandlordDealerAddressRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordDealerAddress::class;
    }
}
