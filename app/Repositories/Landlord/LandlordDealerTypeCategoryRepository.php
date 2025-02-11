<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordDealerTypeCategory;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordDealerTypeCategory $model
 */
class LandlordDealerTypeCategoryRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordDealerTypeCategory::class;
    }
}
