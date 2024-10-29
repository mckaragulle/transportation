<?php

namespace App\Repositories;

use App\Models\District;
use Illuminate\Database\Eloquent\Model;

/**
 * @var District $model
 */
class DistrictRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return District::class;
    }
}
