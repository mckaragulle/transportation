<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordAccountFile;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordAccountFile $model
 */
class LandlordAccountFileRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordAccountFile::class;
    }
}
