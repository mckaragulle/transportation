<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\HgsTypeCategory;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var HgsTypeCategory $model
 */
class HgsTypeCategoryRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return HgsTypeCategory::class;
    }
}
