<?php

namespace App\Repositories;

use App\Models\AccountOfficer;
use Illuminate\Database\Eloquent\Model;

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
