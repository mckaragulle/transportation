<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\Bank;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

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
