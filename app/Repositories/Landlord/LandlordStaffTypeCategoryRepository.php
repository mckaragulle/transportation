<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordStaffTypeCategory;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordStaffTypeCategory $model
 */
class LandlordStaffTypeCategoryRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordStaffTypeCategory::class;
    }
}
