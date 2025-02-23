<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\StaffFile;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var StaffFile $model
 */
class StaffFileRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return StaffFile::class;
    }
}
