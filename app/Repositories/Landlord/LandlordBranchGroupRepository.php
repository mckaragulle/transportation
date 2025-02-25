<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordBranchGroup;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordBranchGroup $model
 */
class LandlordBranchGroupRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordBranchGroup::class;
    }
}
