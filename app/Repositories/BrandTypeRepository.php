<?php

namespace App\Repositories;

use App\Models\BrandType;
use Illuminate\Database\Eloquent\Model;

/**
 * @var BrandType $model
 */
class BrandTypeRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return BrandType::class;
    }
}
