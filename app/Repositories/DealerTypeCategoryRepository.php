<?php

namespace App\Repositories;

use App\Models\DealerTypeCategory;
use Illuminate\Database\Eloquent\Model;

/**
 * @var DealerTypeCategory $model
 */
class DealerTypeCategoryRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return DealerTypeCategory::class;
    }
}
