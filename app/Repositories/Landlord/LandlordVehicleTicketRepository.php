<?php

namespace App\Repositories\Landlord;

use App\Models\Landlord\LandlordVehicleTicket;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

/**
 * @var LandlordVehicleTicket $model
 */
class LandlordVehicleTicketRepository extends BaseRepository implements RepositoryInterface
{
    const PAGE_SIZE = 20;

    protected function model(): string|Model
    {
        return LandlordVehicleTicket::class;
    }
}
