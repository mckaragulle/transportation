<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordSector;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordSector $model
 */
class LandlordSectorRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordSector::class;
    }
}
