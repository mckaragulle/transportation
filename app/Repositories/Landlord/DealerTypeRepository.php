<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\DealerType;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

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
