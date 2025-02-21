<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\Admin;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * @var Admin $model
 */
class AdminRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return Admin::class;
    }
}
