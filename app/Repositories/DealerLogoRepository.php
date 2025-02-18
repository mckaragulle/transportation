<?php

namespace App\Repositories;

use App\Models\Tenant\DealerLogo;
use Illuminate\Database\Eloquent\Model;

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
