<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\AccountOfficer;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var AccountOfficer $model
 */
class AccountOfficerRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return AccountOfficer::class;
    }
}
