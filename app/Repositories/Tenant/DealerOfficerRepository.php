<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\DealerOfficer;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

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
