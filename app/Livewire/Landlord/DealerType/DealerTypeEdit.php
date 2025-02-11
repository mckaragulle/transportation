<?php

namespace App\Livewire\Landlord\DealerType;

use App\Models\Tenant\DealerType;
use App\Services\DealerTypeCategoryService;
use App\Services\DealerTypeService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class DealerTypeEdit extends Component
{
    use LivewireAlert;

    public null|Collection $dealerTypeCategories;
    public null|Collection $dealerTypes;

    public ?DealerType $dealerType = null;

    public null|int $dealer_type_category_id = null;
    public null|int $dealer_type_id = null;
    public null|string $name;
    public bool $status = true;

    protected DealerTypeCategoryService $dealerTypeCategoryService;
    protected DealerTypeService $dealerTypeService;
    /**
     * List of add/edit form rules
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
            ],
            'dealer_type_category_id' => [
                'required',
                'exists:dealer_type_categories,id',
            ],
            'dealer_type_id' => [
                'nullable',
                'exists:dealer_types,id',
            ],
            'status' => [
                'in:true,false,null,0,1,active,passive,',
                'nullable',
            ],
        ];
    }

    protected $messages = [
        'dealer_type_category_id.required' => 'Lütfen bayi kategorisini seçiniz.',
        'dealer_type_category_id.exists' => 'Lütfen geçerli bir bayi kategorisi seçiniz.',
        'dealer_type_id.exists' => 'Lütfen geçerli bir bayi seçiniz.',
        'name.required' => 'Bayi adını yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null, DealerTypeCategoryService $dealerTypeCategoryService, DealerTypeService $dealerTypeService)
    {
        if (!is_null($id)) {
            $this->dealerType = $dealerTypeService->findById($id);
            $this->dealer_type_category_id = $this->dealerType->dealer_type_category_id;
            $this->dealer_type_id = $this->dealerType->dealer_type_id??null;
            $this->name = $this->dealerType->name??null;
            $this->status = $this->dealerType->status;
            $this->dealerTypeCategories = $dealerTypeCategoryService->all();
            $this->dealerTypes = DealerType::query()->where(['dealer_type_category_id' => $this->dealer_type_category_id])->with('dealer_type')->orderBy('id')->get(['id', 'dealer_type_id', 'name']);

        } else {
            return $this->redirect(route('dealerTypes.list'));
        }
    }

    public function render()
    {
        return view('livewire.landlord.dealer-type.dealer-type-edit');
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
            $this->dealerType->dealer_type_category_id = $this->dealer_type_category_id;
            $this->dealerType->dealer_type_id = $this->dealer_type_id ?? null;
            $this->dealerType->name = $this->name;
            $this->dealerType->status = $this->status == false ? 0 : 1;
            $this->dealerType->save();

            $msg = 'Bayi güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Bayi güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }

    public function updatedDealerTypeCategoryId()
    {
        $this->dealerTypes = DealerType::query()->where(['dealer_type_category_id' => $this->dealer_type_category_id])->with('dealer_type')->orderBy('id')->get(['id', 'dealer_type_id', 'name']);
    }
}
