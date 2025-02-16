<?php

namespace App\Livewire\Landlord\Bank;

use App\Services\Landlord\LandlordBankService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class BankEdit extends Component
{
    use LivewireAlert;

    public null|Collection $vehicleBrands;

    public $bank;

    public null|string $name;
    public null|string $phone;
    public null|string $fax;
    public null|string $email;
    public null|string $website;
    public null|string $eft;
    public null|string $swift;

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

    public function mount($id = null, LandlordBankService $bankService)
    {
        if (!is_null($id)) {
            $this->bank = $bankService->findById($id);
            $this->name = $this->bank->name;
            $this->phone = $this->bank->phone;
            $this->fax = $this->bank->fax;
            $this->email = $this->bank->email;
            $this->website = $this->bank->website;
            $this->eft = $this->bank->eft;
            $this->swift = $this->bank->swift;
            $this->status = $this->bank->status;
        } else {
            return $this->redirect(route('banks.list'));
        }
    }

    public function render()
    {
        return view('livewire.landlord.bank.bank-edit');
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
            $this->bank->bank = $this->bank;
            $this->bank->name = $this->name;
            $this->bank->phone = $this->phone;
            $this->bank->fax = $this->fax;
            $this->bank->email = $this->email;
            $this->bank->website = $this->website;
            $this->bank->eft = $this->eft;
            $this->bank->swift = $this->swift;
            
            $this->bank->status = $this->status == false ? 0 : 1;
            $this->bank->save();

            $msg = 'Banka güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Banka güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
