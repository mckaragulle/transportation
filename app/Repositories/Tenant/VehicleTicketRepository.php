<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\VehicleTicket;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var VehicleTicket $model
 */
class VehicleTicketRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return VehicleTicket::class;
    }
}
