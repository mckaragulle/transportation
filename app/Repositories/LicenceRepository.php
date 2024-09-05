<?php

namespace App\Repositories;

use App\Models\Licence;
use Illuminate\Database\Eloquent\Model;

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
