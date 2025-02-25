<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordBranchSector;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordBranchSector $model
 */
class LandlordBranchSectorRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordBranchSector::class;
    }
}
