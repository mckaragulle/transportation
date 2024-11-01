<?php

namespace App\Livewire\AccountBank;
use App\Services\AccountBankService;
use App\Services\AccountService;
use App\Services\BankService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class AccountBankCreate extends Component
{
    use LivewireAlert;

    public null|Collection $accounts = null;
    public null|Collection $banks = null;
    public null|int $account_id = null;
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
        'account_id.required' => 'Lütfen müşteri seçiniz.',
        'account_id.exists' => 'Lütfen geçerli bir müşteri seçiniz.',
        'bank_id.required' => 'Lütfen banka seçiniz.',
        'bank_id.exists' => 'Lütfen geçerli bir banka seçiniz.',
        'iban.required' => 'Lütfen iban adresini yazınız.',
        'iban.unique' => 'Bu iban adresi zaten kullanılmaktadır.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.account-bank.account-bank-create');
    }

    public function mount(AccountService $accountService, BankService $bankService)
    {
        $this->accounts = $accountService->all(['id', 'name', 'shortname']);
        $this->banks = $bankService->all(['id', 'name']);
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(AccountBankService $accountBankService)
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $account = $accountBankService->create([
                'account_id' => $this->account_id ?? null,
                'bank_id' => $this->bank_id ?? null,
                'iban' => $this->iban ?? null,
                'status' => $this->status == false ? 0 : 1,
            ]);


            $this->dispatch('pg:eventRefresh-AccountBankTable');
            $msg = 'Cari banka bilgisi oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset();
        } catch (\Exception $exception) {
            $error = "Cari banka bilgisi oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}