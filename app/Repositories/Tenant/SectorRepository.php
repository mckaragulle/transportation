<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\Sector;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var Sector $model
 */
class SectorRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return Sector::class;
    }
}
