<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordHgsTypeCategory;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordHgsTypeCategory $model
 */
class LandlordHgsTypeCategoryRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordHgsTypeCategory::class;
    }
}
