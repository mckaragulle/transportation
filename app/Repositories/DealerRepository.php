<?php

namespace App\Repositories;

use App\Models\Dealer;
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
