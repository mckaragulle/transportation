<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordBranchType;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordBranchType $model
 */
class LandlordBranchTypeRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordBranchType::class;
    }
}
