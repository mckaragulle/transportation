<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\VehiclePropertyCategory;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var VehiclePropertyCategory $model
 */
class VehiclePropertyCategoryRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return VehiclePropertyCategory::class;
    }
}
