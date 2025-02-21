<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordAccount;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * @var LandlordAccount $model
 */
class LandlordAccountRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordAccount::class;
    }
}
