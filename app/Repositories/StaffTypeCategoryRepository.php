<?php

namespace App\Repositories;

use App\Models\StaffTypeCategory;
use Illuminate\Database\Eloquent\Model;

/**
 * @var StaffTypeCategory $model
 */
class StaffTypeCategoryRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return StaffTypeCategory::class;
    }
}
