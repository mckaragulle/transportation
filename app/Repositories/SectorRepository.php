<?php

namespace App\Repositories;

use App\Models\Tenant\Sector;
use Illuminate\Database\Eloquent\Model;

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
