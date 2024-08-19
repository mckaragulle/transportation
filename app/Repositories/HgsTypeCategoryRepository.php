<?php

namespace App\Repositories;

use App\Models\HgsTypeCategory;
use Illuminate\Database\Eloquent\Model;

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
