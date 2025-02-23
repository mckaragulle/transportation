<?php

namespace App\Livewire\Tenant\StaffBank;

use App\Services\Tenant\BankService;
use App\Services\Tenant\StaffBankService;
use App\Services\Tenant\StaffService;
use App\Services\Tenant\DealerService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class StaffBankCreate extends Component
{
    use LivewireAlert;

    public null|Collection $staffs = null;
    public null|Collection $banks = null;
    public null|string $staff_id = null;
    public null|int $bank_id = null;
    public null|string $iban = null;

    public bool $status = true;
    public bool $is_show = false;

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

    public function render()
    {
        return view('livewire.tenant.staff-bank.staff-bank-create');
    }

    public function mount(null|string $id = null, bool $is_show, StaffService $staffService, BankService $bankService)
    {
        $this->staff_id = $id;
        $this->is_show = $is_show;
        $this->staffs = $staffService->all(['id', 'name']);
        $this->banks = $bankService->all(['id', 'name']);
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(StaffBankService $staffBankService)
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $staff = $staffBankService->create([
                'staff_id' => $this->staff_id ?? null,
                'bank_id' => $this->bank_id ?? null,
                'iban' => $this->iban ?? null,
                'status' => $this->status == false ? 0 : 1,
            ]);


            $this->dispatch('pg:eventRefresh-StaffBankTable');
            $msg = 'Personel banka bilgisi oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset();
        } catch (\Exception $exception) {
            $error = "Personel banka bilgisi oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
