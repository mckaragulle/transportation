<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

/**
 * @var Role $model
 */
class RoleRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    /**
     * @return Model|string
     */
    protected function model(): Model|string
    {
        return Role::class;
    }
}
