<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\AccountBank;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var AccountBank $model
 */
class AccountBankRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return AccountBank::class;
    }
}
