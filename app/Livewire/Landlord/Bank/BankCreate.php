<?php

namespace App\Livewire\Landlord\Bank;

use App\Services\Landlord\LandlordBankService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class BankCreate extends Component
{
    use LivewireAlert;

    public null|string $name = null;
    public null|string $phone = null;
    public null|string $fax = null;
    public null|string $email = null;
    public null|string $website = null;
    public null|string $eft = null;
    public null|string $swift = null;

    public bool $status = true;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'name' => ['required'],
        'phone' => ['nullable'],
        'fax' => ['nullable'],
        'email' => ['nullable'],
        'website' => ['nullable'],
        'eft' => ['nullable'],
        'swift' => ['nullable'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'name.required' => 'Banka adını yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.landlord.bank.bank-create');
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(LandlordBankService $bankService)
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $bank = $bankService->create([
                'name' => $this->name,
                'phone' => $this->phone,
                'fax' => $this->fax,
                'email' => $this->email,
                'website' => $this->website??null,
                'eft' => $this->eft??null,
                'swift' => $this->swift??null,
                'status' => $this->status == false ? 0 : 1,
            ]);

            $this->dispatch('pg:eventRefresh-BankTable');
            $msg = 'Banka oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset();
        } catch (\Exception $exception) {
            $error = "Banka oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
