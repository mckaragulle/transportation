<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordAccountGroup;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordAccountGroup $model
 */
class LandlordAccountGroupRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordAccountGroup::class;
    }
}
