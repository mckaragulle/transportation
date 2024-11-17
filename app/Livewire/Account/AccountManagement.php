<?php

namespace App\Livewire\Account;

use App\Models\Account;
use App\Services\AccountService;
use Illuminate\Database\Eloquent\Model;
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
    }

    public function render()
    {
        return view('livewire.account.account-management');
    }
}
