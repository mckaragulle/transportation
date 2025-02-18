<?php

namespace App\Livewire\Landlord\Dealer;

use App\Models\Landlord\LandlordDealer;
use App\Services\Landlord\LandlordDealerService;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class DealerManagement extends Component
{
    public null|LandlordDealer|Model $dealer = null;
    public null|string $number = null;
    public null|string $name = null;
    public null|string $shortname = null;
    public null|string $email = null;
    public null|string $phone = null;
    public null|string $detail = null;
    public null|string $tax = null;
    public null|string $taxoffice = null;
    public bool $status = true;

    public function mount($id = null, LandlordDealerService $dealerService)
    {
        $this->dealer = $dealerService->findById($id);
        $this->status = $this->dealer->status;
        $this->number = $this->dealer->number;
        $this->name = $this->dealer->name;
        $this->shortname = $this->dealer->shortname;
        $this->email = $this->dealer->email;
        $this->phone = $this->dealer->phone;
        $this->detail = $this->dealer->detail;
        $this->tax = $this->dealer->tax;
        $this->taxoffice = $this->dealer->taxoffice;
    }

    public function render()
    {
        return view('livewire.landlord.dealer.dealer-management');
    }
}
