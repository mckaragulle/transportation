<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\AccountAddress;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var AccountAddress $model
 */
class AccountAddressRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return AccountAddress::class;
    }
}
