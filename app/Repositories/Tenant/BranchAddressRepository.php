<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\BranchAddress;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var BranchAddress $model
 */
class BranchAddressRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return BranchAddress::class;
    }
}
