<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\DealerLogo;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var DealerLogo $model
 */
class DealerLogoRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return DealerLogo::class;
    }
}
