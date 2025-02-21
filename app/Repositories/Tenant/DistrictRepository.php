<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\District;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

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
