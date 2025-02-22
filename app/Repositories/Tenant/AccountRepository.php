<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\Account;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * @var Account $model
 */
class AccountRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return Account::class;
    }
}
