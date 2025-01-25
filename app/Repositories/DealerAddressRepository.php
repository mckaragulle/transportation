<?php

namespace App\Repositories;

use App\Models\DealerAddress;
use Illuminate\Database\Eloquent\Model;

/**
 * @var DealerAddress $model
 */
class DealerAddressRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return DealerAddress::class;
    }
}
