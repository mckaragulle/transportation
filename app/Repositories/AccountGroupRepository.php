<?php

namespace App\Repositories;

use App\Models\AccountGroup;
use App\Models\Tenant\AccountOfficer;
use Illuminate\Database\Eloquent\Model;

/**
 * @var AccountOfficer $model
 */
class AccountGroupRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return AccountGroup::class;
    }
}
