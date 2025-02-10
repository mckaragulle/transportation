<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordAccountType;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordAccountType $model
 */
class LandlordAccountTypeRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordAccountType::class;
    }
}
