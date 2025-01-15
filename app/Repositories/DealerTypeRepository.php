<?php

namespace App\Repositories;

use App\Models\DealerType;
use Illuminate\Database\Eloquent\Model;

/**
 * @var DealerType $model
 */
class DealerTypeRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return DealerType::class;
    }
}
