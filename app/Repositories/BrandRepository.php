<?php

namespace App\Repositories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Model;

/**
 * @var Brand $model
 */
class BrandRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return Brand::class;
    }
}
