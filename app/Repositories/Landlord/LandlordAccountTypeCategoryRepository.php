<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordAccountTypeCategory;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordAccountTypeCategory $model
 */
class LandlordAccountTypeCategoryRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordAccountTypeCategory::class;
    }
}
