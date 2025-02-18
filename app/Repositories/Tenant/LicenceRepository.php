<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\Licence;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var Licence $model
 */
class LicenceRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return Licence::class;
    }
}
