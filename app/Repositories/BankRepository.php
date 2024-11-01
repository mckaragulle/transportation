<?php

namespace App\Repositories;

use App\Models\Bank;
use Illuminate\Database\Eloquent\Model;

/**
 * @var Bank $model
 */
class BankRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return Bank::class;
    }
}
