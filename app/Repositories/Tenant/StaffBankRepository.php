<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\StaffBank;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var StaffBank $model
 */
class StaffBankRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return StaffBank::class;
    }
}
