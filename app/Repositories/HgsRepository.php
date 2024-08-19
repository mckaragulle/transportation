<?php

namespace App\Repositories;

use App\Models\Hgs;
use Illuminate\Database\Eloquent\Model;

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
