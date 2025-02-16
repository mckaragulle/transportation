<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\AccountGroup;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var AccountGroup $model
 */
class AccountGroupRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return AccountGroup::class;
    }
}
