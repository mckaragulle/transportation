<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordGroup;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordGroup $model
 */
class LandlordGroupRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordGroup::class;
    }
}
