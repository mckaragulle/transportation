<?php

namespace App\Livewire\Tenant\Account;

use App\Models\Tenant\Account;
use App\Services\Tenant\AccountService;
use Livewire\Component;

class AccountManagement extends Component
{
    public null|Account $account = null;
    public null|string $number = null;
    public null|string $name = null;
    public null|string $shortname = null;
    public null|string $email = null;
    public null|string $phone = null;
    public null|string $detail = null;
    public null|string $tax = null;
    public null|string $taxoffice = null;
    public bool $status = true;

    public function mount($id = null, AccountService $accountService)
    {
        $this->account = $accountService->findById($id);
        $this->status = $this->account->status;
        $this->number = $this->account->number;
        $this->name = $this->account->name;
        $this->shortname = $this->account->shortname;
        $this->email = $this->account->email;
        $this->phone = $this->account->phone;
        $this->detail = $this->account->detail;
        $this->tax = $this->account->tax;
        $this->taxoffice = $this->account->taxoffice;
    }

    public function render()
    {
        return view('livewire.tenant.account.account-management');
    }
}
