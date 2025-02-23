<?php

namespace App\Livewire\Tenant\StaffBank;

use App\Services\Tenant\BankService;
use App\Services\Tenant\StaffBankService;
use App\Services\Tenant\StaffService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class StaffBankEdit extends Component
{
    use LivewireAlert;

    public ?Model $staffBank = null;
    public null|Collection $staffs = null;
    public null|Collection $banks = null;

    public null|string $staff_id = null;
    public null|int $bank_id = null;
    public null|string $iban = null;

    public bool $status = true;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'staff_id' => ['required', 'exists:tenant.staffs,id'],
        'bank_id' => ['required', 'exists:tenant.banks,id'],
        'iban' => ['required', 'unique:tenant.staff_banks'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'staff_id.required' => 'Lütfen personel seçiniz.',
        'staff_id.exists' => 'Lütfen geçerli bir personel seçiniz.',
        'bank_id.required' => 'Lütfen banka seçiniz.',
        'bank_id.exists' => 'Lütfen geçerli bir banka seçiniz.',
        'iban.required' => 'Lütfen iban adresini yazınız.',
        'iban.unique' => 'Bu iban adresi zaten kullanılmaktadır.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null, StaffService $staffService, BankService $bankService, StaffBankService $staffBankService)
    {
        if (!is_null($id)) {
            $this->staffBank = $staffBankService->findById($id);
            $this->staffs = $staffService->all(['id', 'name']);
            $this->banks = $bankService->all(['id', 'name']);

            $this->staff_id = $this->staffBank->staff_id;
            $this->bank_id = $this->staffBank->bank_id;
            $this->iban = $this->staffBank->iban;
            $this->status = $this->staffBank->status;
        } else {
            return $this->redirect(route('staff_banks.list'));
        }
    }

    public function render()
    {
        return view('livewire.tenant.staff-bank.staff-bank-edit');
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
            $this->staffBank->staff_id = $this->staff_id;
            $this->staffBank->bank_id = $this->bank_id;

            $this->staffBank->iban = $this->iban ?? null;

            $this->staffBank->status = $this->status == false ? 0 : 1;
            $this->staffBank->save();

            $msg = 'Personel banka bilgisi güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Personel banka bilgisi güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
