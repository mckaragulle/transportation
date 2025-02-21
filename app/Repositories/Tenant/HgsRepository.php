<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\Hgs;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var Hgs $model
 */
class HgsRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return Hgs::class;
    }
}
