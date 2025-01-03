<?php

namespace App\Livewire\AccountTypeCategory;

use App\Models\AccountTypeCategory;
use App\Services\AccountTypeCategoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class AccountTypeCategoryEdit extends Component
{
    use LivewireAlert;

    public null|AccountTypeCategory $accountTypeCategory;

    public null|string $name;

    public bool $is_required = true;
    public bool $is_multiple = false;
    public bool $status = true;

    protected AccountTypeCategoryService $accountTypeCategoryService;
    /**
     * List of add/edit form rules
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
            ],
            'is_required' => [
                'in:true,false,null,0,1,active,passive,',
                'nullable',
            ],
            'is_multiple' => [
                'in:true,false,null,0,1,active,passive,',
                'nullable',
            ],
            'status' => [
                'in:true,false,null,0,1,active,passive,',
                'nullable',
            ],
        ];
    }

    protected $messages = [
        'name.required' => 'Cari kategorisi yazınız.',
        'is_required.in' => 'Lütfen geçerli bir durum seçiniz.',
        'is_multiple.in' => 'Lütfen geçerli bir durum seçiniz.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null, AccountTypeCategoryService $accountTypeCategoryService)
    {
        if (!is_null($id)) {

            $this->accountTypeCategory = $accountTypeCategoryService->findById($id);
            $this->name = $this->accountTypeCategory->name;
            $this->is_required = $this->accountTypeCategory->is_required;
            $this->is_multiple = $this->accountTypeCategory->is_multiple;
            $this->status = $this->accountTypeCategory->status;
        } else {
            return $this->redirect(route('account_type_categories.list'));
        }
    }

    public function render()
    {
        return view('livewire.account-type-category.account-type-category-edit');
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
            $this->accountTypeCategory->name = $this->name;
            $this->accountTypeCategory->is_required = ($this->is_required == false ? 0 : 1);
            $this->accountTypeCategory->is_multiple = ($this->is_multiple == false ? 0 : 1);
            $this->accountTypeCategory->status = ($this->status == false ? 0 : 1);
            $this->accountTypeCategory->save();

            $msg = 'Cari kategorisi güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Cari kategorisi güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
