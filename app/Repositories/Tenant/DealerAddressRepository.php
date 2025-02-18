<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\DealerAddress;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

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
