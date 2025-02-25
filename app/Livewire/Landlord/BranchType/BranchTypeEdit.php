<?php

namespace App\Livewire\Landlord\BranchType;

use App\Models\Landlord\LandlordBranchType;
use App\Models\Landlord\LandlordLicenceType;
use App\Services\Landlord\LandlordBranchTypeCategoryService;
use App\Services\Landlord\LandlordBranchTypeService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class BranchTypeEdit extends Component
{
    use LivewireAlert;

    public null|Collection $branchTypeCategories;
    public null|Collection $branchTypes;

    public ?LandlordBranchType $branchType = null;

    public null|int $branch_type_category_id = null;
    public null|int $branch_type_id = null;
    public null|string $name;
    public bool $status = true;

    protected LandlordBranchTypeCategoryService $branchTypeCategoryService;
    protected LandlordBranchTypeService $branchTypeService;

    /**
     * List of add/edit form rules
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
            ],
            'branch_type_category_id' => [
                'required',
                'exists:branch_type_categories,id',
            ],
            'branch_type_id' => [
                'nullable',
                'exists:branch_types,id',
            ],
            'status' => [
                'in:true,false,null,0,1,active,passive,',
                'nullable',
            ],
        ];
    }

    protected $messages = [
        'branch_type_category_id.required' => 'Lütfen şube kategorisini seçiniz.',
        'branch_type_category_id.exists' => 'Lütfen geçerli bir şube kategorisi seçiniz.',
        'branch_type_id.exists' => 'Lütfen geçerli bir şube seçiniz.',
        'name.required' => 'Şube adını yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null, LandlordBranchTypeCategoryService $branchTypeCategoryService, LandlordBranchTypeService $branchTypeService)
    {
        if (!is_null($id)) {
            $this->branchType = $branchTypeService->findById($id);
            $this->branch_type_category_id = $this->branchType->branch_type_category_id;
            $this->branch_type_id = $this->branchType->branch_type_id??null;
            $this->name = $this->branchType->name??null;
            $this->status = $this->branchType->status;
            $this->branchTypeCategories = $branchTypeCategoryService->all();
            $this->branchTypes = LandlordBranchType::query()->where(['branch_type_category_id' => $this->branch_type_category_id])->with('branch_type')->orderBy('id')->get(['id', 'branch_type_id', 'name']);

        } else {
            return $this->redirect(route('branchTypes.list'));
        }
    }

    public function render()
    {
        return view('livewire.landlord.branch-type.branch-type-edit');
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
            $this->branchType->branch_type_category_id = $this->branch_type_category_id;
            $this->branchType->branch_type_id = $this->branch_type_id ?? null;
            $this->branchType->name = $this->name;
            $this->branchType->status = $this->status == false ? 0 : 1;
            $this->branchType->save();

            $msg = 'Şube kategori seçeneği güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Şube kategori seçeneği güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }

    public function updatedBranchTypeCategoryId()
    {
        $this->branchTypes = LandlordBranchType::query()->where(['branch_type_category_id' => $this->branch_type_category_id])->with('branch_type')->orderBy('id')->get(['id', 'branch_type_id', 'name']);
    }
}
