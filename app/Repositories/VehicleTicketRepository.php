<?php

namespace App\Repositories;

use App\Models\VehicleTicket;
use Illuminate\Database\Eloquent\Model;

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
