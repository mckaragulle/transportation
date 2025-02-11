<?php

namespace App\Livewire\Landlord\DealerType;

use App\Models\Landlord\LandlordDealerType;
use App\Services\Landlord\LandlordDealerTypeCategoryService;
use App\Services\Landlord\LandlordDealerTypeService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class DealerTypeCreate extends Component
{
    use LivewireAlert;

    public null|Collection $dealerTypeCategories;
    public null|Collection $dealerTypes;
    public null|string|int $dealer_type_category_id = null;
    public null|string|int $dealer_type_id = null;
    public null|string $name;

    public bool $status = true;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'dealer_type_category_id' => ['required', 'exists:landlord.dealer_type_categories,id'],
        'dealer_type_id' => ['nullable', 'exists:landlord.dealer_types,id'],
        'name' => ['required'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'dealer_type_category_id.required' => 'Lütfen cari kategorisini seçiniz.',
        'dealer_type_category_id.exists' => 'Lütfen geçerli bir cari kategorisi seçiniz.',
        'dealer_type_id.exists' => 'Lütfen geçerli bir cari seçiniz.',
        'name.required' => 'Cari adını yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.landlord.dealer-type.dealer-type-create');
    }

    public function mount(LandlordDealerTypeCategoryService $dealerTypeCategoryService)
    {
        $this->dealerTypeCategories = $dealerTypeCategoryService->all(['id', 'name']);
        $this->dealerTypes = LandlordDealerType::query()->where(['dealer_type_category_id' => $this->dealer_type_category_id])->with('dealer_type')->orderBy('id')->get(['id', 'dealer_type_id', 'name']);
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(LandlordDealerTypeService $dealerTypeService)
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $dealerType = $dealerTypeService->create([
                'dealer_type_category_id' => $this->dealer_type_category_id ?? null,
                'dealer_type_id' => $this->dealer_type_id ?? null,
                'name' => $this->name,
                'status' => $this->status == false ? 0 : 1,
            ]);

            $this->dispatch('pg:eventRefresh-DealerTypeTable');
            $msg = 'Bayi seçeneği oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset(['name']);
        } catch (\Exception $exception) {
            $error = "Bayi seçeneği oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }

    public function updatedDealerTypeCategoryId()
    {
        $this->dealerTypes = LandlordDealerType::query()->where(['dealer_type_category_id' => $this->dealer_type_category_id])->with('dealer_type')->orderBy('id')->get(['id', 'dealer_type_id', 'name']);
    }
}
