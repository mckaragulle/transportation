<?php

namespace App\Repositories;

use App\Models\Fined;
use Illuminate\Database\Eloquent\Model;

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
