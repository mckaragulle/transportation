<?php

namespace App\Repositories;

use App\Models\HgsType;
use Illuminate\Database\Eloquent\Model;

/**
 * @var HgsType $model
 */
class HgsTypeRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return HgsType::class;
    }
}
