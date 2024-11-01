<?php

namespace App\Repositories;

use App\Models\AccountBank;
use Illuminate\Database\Eloquent\Model;

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
