<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\BranchType;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var BranchType $model
 */
class BranchTypeRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return BranchType::class;
    }
}
