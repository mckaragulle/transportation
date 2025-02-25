<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\BranchTypeCategory;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var BranchTypeCategory $model
 */
class BranchTypeCategoryRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return BranchTypeCategory::class;
    }
}
