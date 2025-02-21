<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordDealerOfficer;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordDealerOfficer $model
 */
class LandlordDealerOfficerRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordDealerOfficer::class;
    }
}
