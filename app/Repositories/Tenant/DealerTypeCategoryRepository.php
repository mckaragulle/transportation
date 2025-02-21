<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\DealerTypeCategory;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

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
