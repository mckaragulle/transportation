<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

/**
 * @var Role $model
 */
class PermissionRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return Permission::class;
    }
}
