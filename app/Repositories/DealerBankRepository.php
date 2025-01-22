<?php

namespace App\Repositories;

use App\Models\DealerBank;
use Illuminate\Database\Eloquent\Model;

/**
 * @var DealerBank $model
 */
class DealerBankRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return DealerBank::class;
    }
}
