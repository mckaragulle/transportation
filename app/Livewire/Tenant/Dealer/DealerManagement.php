<?php

namespace App\Livewire\Tenant\Dealer;

use App\Models\Tenant\Dealer;
use App\Services\Tenant\DealerService;
use Livewire\Component;

class DealerManagement extends Component
{
    public null|Dealer $dealer = null;
    public null|string $number = null;
    public null|string $name = null;
    public null|string $shortname = null;
    public null|string $email = null;
    public null|string $phone = null;
    public null|string $detail = null;
    public null|string $tax = null;
    public null|string $taxoffice = null;
    public bool $status = true;

    public function mount($id = null, DealerService $dealerService)
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
        return view('livewire.tenant.dealer.dealer-management');
    }
}
