<?php

namespace App\Livewire\AccountType;

use App\Models\AccountType;
use App\Services\AccountTypeCategoryService;
use App\Services\AccountTypeService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class AccountTypeCreate extends Component
{
    use LivewireAlert;

    public null|Collection $accountTypeCategories;
    public null|Collection $accounTypes;
    public null|int $account_type_category_id = null;
    public null|int $account_type_id = null;
    public null|string $name;

    public bool $status = true;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'account_type_category_id' => ['required', 'exists:account_type_categories,id'],
        'account_type_id' => ['nullable', 'exists:vaccount_types,id'],
        'name' => ['required'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'account_type_category_id.required' => 'Lütfen cari kategorisini seçiniz.',
        'account_type_category_id.exists' => 'Lütfen geçerli bir cari kategorisi seçiniz.',
        'account_type_id.exists' => 'Lütfen geçerli bir cari seçiniz.',
        'name.required' => 'Cari adını yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.account-type.account-type-create');
    }

    public function mount(AccountTypeCategoryService $accountTypeCategoryService)
    {
        $this->accountTypeCategories = $accountTypeCategoryService->all(['id', 'name']);
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(AccountTypeService $accountTypeService)
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $accountType = $accountTypeService->create([
                'account_type_category_id' => $this->account_type_category_id ?? null,
                'account_type_id' => $this->account_type_id ?? null,
                'name' => $this->name,
                'status' => $this->status == false ? 0 : 1,
            ]);

            $this->dispatch('pg:eventRefresh-AccountTypeTable');
            $msg = 'Cari oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset(['name']);
        } catch (\Exception $exception) {
            $error = "Cari oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }

    public function updatedAccountTypeCategoryId()
    {
        $this->accounTypes = AccountType::query()->where(['account_type_category_id' => $this->account_type_category_id])->whereNull('account_type_id')->get(['id', 'name']);
    }
}
