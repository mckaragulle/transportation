<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\BranchBank;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var BranchBank $model
 */
class BranchBankRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return BranchBank::class;
    }
}
