<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordDealerBank;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordDealerBank $model
 */
class LandlordDealerBankRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordDealerBank::class;
    }
}
