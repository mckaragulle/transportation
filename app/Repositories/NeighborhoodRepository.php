<?php

namespace App\Repositories;

use App\Models\Neighborhood;
use Illuminate\Database\Eloquent\Model;

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
