<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\Neighborhood;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var Neighborhood $model
 */
class NeighborhoodRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return Neighborhood::class;
    }
}
