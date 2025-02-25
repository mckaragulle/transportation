<?php

namespace App\Livewire\Landlord\BranchType;

use App\Models\Landlord\LandlordDealerType;
use App\Services\Landlord\LandlordDealerTypeCategoryService;
use App\Services\Landlord\LandlordDealerTypeService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class BranchTypeCreate extends Component
{
    use LivewireAlert;

    public null|Collection $branchTypeCategories;
    public null|Collection $branchTypes;
    public null|string|int $branch_type_category_id = null;
    public null|string|int $branch_type_id = null;
    public null|string $name;

    public bool $status = true;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'branch_type_category_id' => ['required', 'exists:landlord.branch_type_categories,id'],
        'branch_type_id' => ['nullable', 'exists:landlord.branch_types,id'],
        'name' => ['required'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'branch_type_category_id.required' => 'Lütfen şube kategorisini seçiniz.',
        'branch_type_category_id.exists' => 'Lütfen geçerli bir şube kategorisi seçiniz.',
        'branch_type_id.exists' => 'Lütfen geçerli bir şube seçiniz.',
        'name.required' => 'Cari adını yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.landlord.branch-type.branch-type-create');
    }

    public function mount(LandlordDealerTypeCategoryService $branchTypeCategoryService)
    {
        $this->branchTypeCategories = $branchTypeCategoryService->all(['id', 'name']);
        $this->branchTypes = LandlordDealerType::query()->where(['branch_type_category_id' => $this->branch_type_category_id])->with('branch_type')->orderBy('id')->get(['id', 'branch_type_id', 'name']);
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(LandlordDealerTypeService $branchTypeService)
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $branchType = $branchTypeService->create([
                'branch_type_category_id' => $this->branch_type_category_id ?? null,
                'branch_type_id' => $this->branch_type_id ?? null,
                'name' => $this->name,
                'status' => $this->status == false ? 0 : 1,
            ]);

            $this->dispatch('pg:eventRefresh-DealerTypeTable');
            $msg = 'Bayi kategori seçeneği oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset(['name']);
        } catch (\Exception $exception) {
            $error = "Bayi kategori seçeneği oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }

    public function updatedDealerTypeCategoryId()
    {
        $this->branchTypes = LandlordDealerType::query()->where(['branch_type_category_id' => $this->branch_type_category_id])->with('branch_type')->orderBy('id')->get(['id', 'branch_type_id', 'name']);
    }
}
