<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\DealerBank;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

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
