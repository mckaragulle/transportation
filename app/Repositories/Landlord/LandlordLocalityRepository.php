<?php

namespace App\Repositories\Landlord;

use App\Models\Tenant\Locality;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordLocality $model
 */
class LandlordLocalityRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordLocality::class;
    }
}
