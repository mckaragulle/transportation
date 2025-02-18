<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordHgsType;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordHgsType $model
 */
class LandlordHgsTypeRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordHgsType::class;
    }
}
