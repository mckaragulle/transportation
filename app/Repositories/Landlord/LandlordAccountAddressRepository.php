<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordAccountAddress;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordAccountAddress $model
 */
class LandlordAccountAddressRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordAccountAddress::class;
    }
}
