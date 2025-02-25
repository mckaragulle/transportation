<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\BranchOfficer;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var BranchOfficer $model
 */
class BranchOfficerRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return BranchOfficer::class;
    }
}
