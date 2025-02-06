<?php

namespace App\Repositories;

use App\Models\Tenant\LicenceTypeCategory;
use Illuminate\Database\Eloquent\Model;

/**
 * @var LicenceTypeCategory $model
 */
class LicenceTypeCategoryRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LicenceTypeCategory::class;
    }
}
