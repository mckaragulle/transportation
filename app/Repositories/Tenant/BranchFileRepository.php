<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\BranchFile;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var BranchFile $model
 */
class BranchFileRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return BranchFile::class;
    }
}
