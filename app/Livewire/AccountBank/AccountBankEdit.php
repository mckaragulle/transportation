<?php

namespace App\Livewire\AccountBank;

use App\Models\AccountBank;
use App\Services\AccountBankService;
use App\Services\AccountService;
use App\Services\BankService;
use App\Services\DealerService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class AccountBankEdit extends Component
{
    use LivewireAlert;

    public ?AccountBank $accountBank = null;

    public null|Collection $dealers = null;
    public null|Collection $accounts = null;
    public null|Collection $banks = null;

    public null|string $dealer_id = null;
    public null|string $account_id = null;
    public null|int $bank_id = null;
    public null|string $iban = null;

    public bool $status = true;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'account_id' => ['required', 'exists:accounts,id'],
        'bank_id' => ['required', 'exists:banks,id'],
        'iban' => ['required', 'unique:account_banks'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'dealer_id.required' => 'Lütfen bir bayi seçiniz.',
        'dealer_id.exists' => 'Lütfen geçerli bir bayi seçiniz.',
        'account_id.required' => 'Lütfen müşteri seçiniz.',
        'account_id.exists' => 'Lütfen geçerli bir müşteri seçiniz.',
        'bank_id.required' => 'Lütfen banka seçiniz.',
        'bank_id.exists' => 'Lütfen geçerli bir banka seçiniz.',
        'iban.required' => 'Lütfen iban adresini yazınız.',
        'iban.unique' => 'Bu iban adresi zaten kullanılmaktadır.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null, DealerService $dealerService, AccountService $accountService, BankService $bankService, AccountBankService $accountBankService)
    {
        if (!is_null($id)) {
            $this->accountBank = $accountBankService->findById($id);
            $this->dealers = $dealerService->all(['id', 'name']);
            $this->accounts = $accountService->all(['id', 'name']);
            $this->banks = $bankService->all(['id', 'name']);

            if (auth()->getDefaultDriver() == 'dealer') {
                $this->dealer_id = auth()->user()->id;
            } else if (auth()->getDefaultDriver() == 'users') {
                $this->dealer_id = auth()->user()->dealer()->id;
            }
            $this->account_id = $this->accountBank->account_id;
            $this->bank_id = $this->accountBank->bank_id;
            $this->iban = $this->accountBank->iban;
            $this->status = $this->accountBank->status;
        } else {
            return $this->redirect(route('account_banks.list'));
        }
    }

    public function render()
    {
        return view('livewire.account-bank.account-bank-edit');
    }

    /**
     * update the exam data
     *
     * @return void
     */
    public function update()
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $this->accountBank->dealer_id = $this->dealer_id;
            $this->accountBank->account_id = $this->account_id;
            $this->accountBank->bank_id = $this->bank_id;

            $this->accountBank->iban = $this->iban ?? null;

            $this->accountBank->status = $this->status == false ? 0 : 1;
            $this->accountBank->save();

            $msg = 'Cari banka bilgisi güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Cari banka bilgisi güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
