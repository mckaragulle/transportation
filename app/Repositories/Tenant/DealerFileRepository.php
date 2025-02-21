<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\DealerFile;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var DealerFile $model
 */
class DealerFileRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return DealerFile::class;
    }
}
