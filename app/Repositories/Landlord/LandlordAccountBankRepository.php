<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordAccountBank;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordAccountBank $model
 */
class LandlordAccountBankRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordAccountBank::class;
    }
}
