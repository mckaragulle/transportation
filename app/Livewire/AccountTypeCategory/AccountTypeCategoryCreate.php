<?php

namespace App\Livewire\AccountTypeCategory;

use App\Services\AccountTypeCategoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class AccountTypeCategoryCreate extends Component
{
    use LivewireAlert;

    public null|string $name;

    public bool $status = true;

    protected AccountTypeCategoryService $accountTypeCategoryService;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'name' => ['required'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'name.required' => 'Cari kategorisi yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.account-type-category.account-type-category-create');
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(AccountTypeCategoryService $accountTypeCategoryService)
    {
        $this->validate();
        $this->accountTypeCategoryService = $accountTypeCategoryService;
        DB::beginTransaction();
        try {
            $accountTypeCategory = $this->accountTypeCategoryService->create([
                'name' => $this->name,
                'status' => $this->status == false ? 0 : 1,
            ]);

            $this->dispatch('pg:eventRefresh-AccountTypeCategoryTable');
            $msg = 'Cari kategorisi oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset('name');
        } catch (\Exception $exception) {
            $error = "Cari kategorisi oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
