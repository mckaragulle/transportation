<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\User;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var User $model
 */
class UserRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return User::class;
    }
}
