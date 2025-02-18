<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\LicenceTypeCategory;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

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
