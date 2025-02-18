<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\HgsType;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

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
