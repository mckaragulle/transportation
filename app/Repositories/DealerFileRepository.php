<?php

namespace App\Repositories;

use App\Models\DealerFile;
use Illuminate\Database\Eloquent\Model;

/**
 * @var DealerOfficer $model
 */
class DealerFileRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return DealerFile::class;
    }
}
