<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\AccountFile;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var AccountFile $model
 */
class AccountFileRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return AccountFile::class;
    }
}
