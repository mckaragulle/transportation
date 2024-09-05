<?php

namespace App\Repositories;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Model;

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
