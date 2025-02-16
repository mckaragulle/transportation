<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordLicenceTypeCategory;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordLicenceTypeCategory $model
 */
class LandlordLicenceTypeCategoryRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordLicenceTypeCategory::class;
    }
}
