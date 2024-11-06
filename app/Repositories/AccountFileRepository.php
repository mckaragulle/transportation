<?php

namespace App\Repositories;

use App\Models\AccountFile;
use App\Models\AccountOfficer;
use Illuminate\Database\Eloquent\Model;

/**
 * @var AccountOfficer $model
 */
class AccountFileRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return AccountFile::class;
    }
}
