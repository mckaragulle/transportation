<?php

namespace App\Repositories;

use App\Models\Tenant\VehiclePropertyCategory;
use Illuminate\Database\Eloquent\Model;

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
