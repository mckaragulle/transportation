<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\LicenceType;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LicenceType $model
 */
class LicenceTypeRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LicenceType::class;
    }
}
