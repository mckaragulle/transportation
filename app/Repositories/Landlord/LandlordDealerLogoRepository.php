<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordDealerLogo;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordDealerLogo $model
 */
class LandlordDealerLogoRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordDealerLogo::class;
    }
}
