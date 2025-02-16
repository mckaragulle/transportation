<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordAccountOfficerService;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordAccountOfficerService $model
 */
class LandlordAccountOfficerRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordAccountOfficerService::class;
    }
}
