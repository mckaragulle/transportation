<?php

namespace App\Repositories;

use App\Models\AccountAddress;
use Illuminate\Database\Eloquent\Model;

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
