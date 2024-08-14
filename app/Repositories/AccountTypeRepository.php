<?php

namespace App\Repositories;

use App\Models\AccountType;
use Illuminate\Database\Eloquent\Model;

/**
 * @var AccountType $model
 */
class AccountTypeRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return AccountType::class;
    }
}
