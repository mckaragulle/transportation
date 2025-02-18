<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\Fined;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var Fined $model
 */
class FinedRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return Fined::class;
    }
}
