<?php

namespace App\Repositories\Landlord;

use App\Models\LandlordBank\LandlordBank;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordBank $model
 */
class LandlordBankRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordBank::class;
    }
}
