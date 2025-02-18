<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\StaffType;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

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
