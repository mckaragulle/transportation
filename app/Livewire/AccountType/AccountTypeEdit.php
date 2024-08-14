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

class AccountTypeEdit extends Component
{
    use LivewireAlert;

    public null|Collection $accountTypeCategories;

    public ?AccountType $accountType = null;

    public null|int $account_type_category_id = null;
    public null|int $account_type_id = null;
    public null|string $name;
    public bool $status = true;

    protected AccountTypeCategoryService $vehicleBrandService;
    /**
     * List of add/edit form rules
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
            ],
            'account_type_category_id' => [
                'required',
                'exists:account_type_categories,id',
            ],
            'account_type_id' => [
                'nullable',
                'exists:vehicle_properties,id',
            ],
            'status' => [
                'in:true,false,null,0,1,active,passive,',
                'nullable',
            ],
        ];
    }

    protected $messages = [
        'account_type_category_id.required' => 'Lütfen özellik kategorisini seçiniz.',
        'account_type_category_id.exists' => 'Lütfen geçerli bir özellik kategorisi seçiniz.',
        'account_type_id.exists' => 'Lütfen geçerli bir özellik seçiniz.',
        'name.required' => 'Cari adını yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null, AccountTypeCategoryService $accountTypeCategoryService, AccountTypeService $accountTypeService)
    {
        if (!is_null($id)) {
            $this->accountType = $accountTypeService->findById($id);
            $this->account_type_category_id = $this->accountType->account_type_category_id;
            $this->account_type_id = $this->accountType->account_type_id??null;
            $this->name = $this->accountType->name;
            $this->status = $this->accountType->status;
            $this->accountTypeCategories = $accountTypeCategoryService->all();
        } else {
            return $this->redirect(route('vehicleProperties.list'));
        }
    }

    public function render()
    {
        return view('livewire.account-type.account-type-edit');
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
            $this->accountType->account_type_category_id = $this->account_type_category_id;
            $this->accountType->account_type_id = $this->account_type_id;
            $this->accountType->name = $this->name;
            $this->accountType->status = $this->status == false ? 0 : 1;
            $this->accountType->save();

            $msg = 'Cari güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Cari güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
