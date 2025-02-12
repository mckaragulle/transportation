<?php

namespace App\Repositories;

use App\Models\Locality;
use Illuminate\Database\Eloquent\Model;

/**
 * @var Locality $model
 */
class LocalityRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return Locality::class;
    }
}
