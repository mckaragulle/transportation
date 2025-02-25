<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\Branch;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var Branch $model
 */
class BranchRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return Branch::class;
    }
}
