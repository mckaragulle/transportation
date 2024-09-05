<?php

namespace App\Repositories;

use App\Models\StaffType;
use Illuminate\Database\Eloquent\Model;

/**
 * @var StaffType $model
 */
class StaffTypeRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return StaffType::class;
    }
}
