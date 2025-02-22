<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\StaffAddress;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var StaffAddress $model
 */
class StaffAddressRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return StaffAddress::class;
    }
}
