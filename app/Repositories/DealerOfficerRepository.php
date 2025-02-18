<?php

namespace App\Repositories;

use App\Models\Tenant\DealerOfficer;
use Illuminate\Database\Eloquent\Model;

/**
 * @var DealerOfficer $model
 */
class DealerOfficerRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return DealerOfficer::class;
    }
}
