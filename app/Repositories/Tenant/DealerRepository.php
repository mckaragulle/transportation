<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\Dealer;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * @var Dealer $model
 */
class DealerRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return Dealer::class;
    }
}
