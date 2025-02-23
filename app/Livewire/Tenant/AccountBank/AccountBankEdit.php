<?php

namespace App\Livewire\Tenant\AccountBank;

use App\Services\Tenant\BankService;
use App\Services\Tenant\AccountBankService;
use App\Services\Tenant\AccountService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class AccountBankEdit extends Component
{
    use LivewireAlert;

    public ?Model $accountBank = null;

    public null|Collection $accounts = null;
    public null|Collection $banks = null;

    public null|string $account_id = null;
    public null|int $bank_id = null;
    public null|string $iban = null;

    public bool $status = true;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'bank_id' => ['required', 'exists:tenant.banks,id'],
        'iban' => ['required', 'unique:tenant.account_banks'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'account_id.required' => 'Lütfen müşteri seçiniz.',
        'account_id.exists' => 'Lütfen geçerli bir müşteri seçiniz.',
        'bank_id.required' => 'Lütfen banka seçiniz.',
        'bank_id.exists' => 'Lütfen geçerli bir banka seçiniz.',
        'iban.required' => 'Lütfen iban adresini yazınız.',
        'iban.unique' => 'Bu iban adresi zaten kullanılmaktadır.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null, AccountService $accountService, BankService $bankService, AccountBankService $accountBankService)
    {
        if (!is_null($id)) {
            $this->accountBank = $accountBankService->findById($id);
            $this->accounts = $accountService->all(['id', 'name']);
            $this->banks = $bankService->all(['id', 'name']);

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
        return view('livewire.tenant.account-bank.account-bank-edit');
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
