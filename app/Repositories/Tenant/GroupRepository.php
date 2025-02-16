<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\Group;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var Group $model
 */
class GroupRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return Group::class;
    }
}
