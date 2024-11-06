<?php

namespace App\Repositories;

use App\Models\Group;
use Illuminate\Database\Eloquent\Model;

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
