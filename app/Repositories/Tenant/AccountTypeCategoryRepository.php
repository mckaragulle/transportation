<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\AccountTypeCategory;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var AccountTypeCategory $model
 */
class AccountTypeCategoryRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return AccountTypeCategory::class;
    }
}
