<?php

namespace App\Livewire\Landlord\DealerBank;

use App\Services\DealerBankService;
use App\Services\BankService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class DealerBankCreate extends Component
{
    use LivewireAlert;

    public null|Collection $banks = null;
    public null|string $dealer_id = null;
    public null|int $bank_id = null;
    public null|string $iban = null;

    public bool $status = true;
    public bool $is_show = false;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'dealer_id' => ['required', 'exists:dealers,id'],
        'bank_id' => ['required', 'exists:banks,id'],
        'iban' => ['required', 'unique:dealer_banks'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'dealer_id.required' => 'Lütfen bir bayi seçiniz.',
        'dealer_id.exists' => 'Lütfen geçerli bir bayi seçiniz.',
        'bank_id.required' => 'Lütfen banka seçiniz.',
        'bank_id.exists' => 'Lütfen geçerli bir banka seçiniz.',
        'iban.required' => 'Lütfen iban adresini yazınız.',
        'iban.unique' => 'Bu iban adresi zaten kullanılmaktadır.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.landlord.dealer-bank.dealer-bank-create');
    }

    public function mount(null|string $id = null, bool $is_show, BankService $bankService)
    {
        $this->dealer_id = $id;
        $this->is_show = $is_show;
        $this->banks = $bankService->all(['id', 'name']);
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(DealerBankService $dealerBankService)
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $dealer = $dealerBankService->create([
                'dealer_id' => $this->dealer_id,
                'bank_id' => $this->bank_id ?? null,
                'iban' => $this->iban ?? null,
                'status' => $this->status == false ? 0 : 1,
            ]);


            $this->dispatch('pg:eventRefresh-DealerBankTable');
            $msg = 'Bayi banka bilgisi oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset();
        } catch (\Exception $exception) {
            $error = "Bayi banka bilgisi oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
