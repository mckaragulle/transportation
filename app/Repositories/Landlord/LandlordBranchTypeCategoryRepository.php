<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordBranchTypeCategory;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordBranchTypeCategory $model
 */
class LandlordBranchTypeCategoryRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordBranchTypeCategory::class;
    }
}
