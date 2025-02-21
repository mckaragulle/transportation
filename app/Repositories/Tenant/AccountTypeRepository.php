<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\AccountType;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

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
