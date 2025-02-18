<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\Staff;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var Staff $model
 */
class StaffRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return Staff::class;
    }
}
