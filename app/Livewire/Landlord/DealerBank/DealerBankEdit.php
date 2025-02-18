<?php

namespace App\Livewire\Landlord\DealerBank;

use App\Models\Tenant\DealerBank;
use App\Services\BankService;
use App\Services\DealerBankService;
use App\Services\Tenant\DealerService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class DealerBankEdit extends Component
{
    use LivewireAlert;

    public ?DealerBank $dealerBank = null;

    public null|Collection $dealers = null;
    public null|Collection $banks = null;

    public null|string $dealer_id = null;
    public null|int $bank_id = null;
    public null|string $iban = null;

    public bool $status = true;

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

    public function mount($id = null, DealerService $dealerService, BankService $bankService, DealerBankService $dealerBankService)
    {
        if (!is_null($id)) {
            $this->dealerBank = $dealerBankService->findById($id);
            $this->dealers = $dealerService->all(['id', 'name']);
            $this->banks = $bankService->all(['id', 'name']);

            if (auth()->getDefaultDriver() == 'dealer') {
                $this->dealer_id = auth()->user()->id;
            } else if (auth()->getDefaultDriver() == 'users') {
                $this->dealer_id = auth()->user()->dealer()->id;
            }

            $this->bank_id = $this->dealerBank->bank_id;
            $this->iban = $this->dealerBank->iban;
            $this->status = $this->dealerBank->status;
        } else {
            return $this->redirect(route('dealer_banks.list'));
        }
    }

    public function render()
    {
        return view('livewire.landlord.dealer-bank.dealer-bank-edit');
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
            $this->dealerBank->dealer_id = $this->dealer_id;
            $this->dealerBank->bank_id = $this->bank_id;

            $this->dealerBank->iban = $this->iban ?? null;

            $this->dealerBank->status = $this->status == false ? 0 : 1;
            $this->dealerBank->save();

            $msg = 'Bayi banka bilgisi güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Bayi banka bilgisi güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
