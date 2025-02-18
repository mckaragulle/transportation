<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordStaffType;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordStaffType $model
 */
class LandlordStaffTypeRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordStaffType::class;
    }
}
