<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\StaffCompetence;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var StaffCompetence $model
 */
class StaffCompetenceRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return StaffCompetence::class;
    }
}
