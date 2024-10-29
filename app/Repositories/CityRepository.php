<?php

namespace App\Repositories;

use App\Models\City;
use Illuminate\Database\Eloquent\Model;

/**
 * @var City $model
 */
class CityRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return City::class;
    }
}
