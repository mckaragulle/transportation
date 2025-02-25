<?php

namespace App\Livewire\Landlord\BranchTypeCategory;

use App\Models\Landlord\LandlordBranchTypeCategory;
use App\Services\Landlord\LandlordBranchTypeCategoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class BranchTypeCategoryEdit extends Component
{
    use LivewireAlert;

    public null|LandlordBranchTypeCategory $branchTypeCategory;

    public null|string $name;

    public bool $is_required = true;
    public bool $is_multiple = false;
    public bool $status = true;

    protected LandlordBranchTypeCategoryService $branchTypeCategoryService;
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
        'name.required' => 'Şube kategorisi yazınız.',
        'is_required.in' => 'Lütfen geçerli bir durum seçiniz.',
        'is_multiple.in' => 'Lütfen geçerli bir durum seçiniz.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null, LandlordBranchTypeCategoryService $branchTypeCategoryService)
    {
        if (!is_null($id)) {

            $this->branchTypeCategory = $branchTypeCategoryService->findById($id);
            $this->name = $this->branchTypeCategory->name;
            $this->is_required = $this->branchTypeCategory->is_required;
            $this->is_multiple = $this->branchTypeCategory->is_multiple;
            $this->status = $this->branchTypeCategory->status;
        } else {
            return $this->redirect(route('branch_type_categories.list'));
        }
    }

    public function render()
    {
        return view('livewire.landlord.branch-type-category.branch-type-category-edit');
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
            $this->branchTypeCategory->name = $this->name;
            $this->branchTypeCategory->is_required = ($this->is_required == false ? 0 : 1);
            $this->branchTypeCategory->is_multiple = ($this->is_multiple == false ? 0 : 1);
            $this->branchTypeCategory->status = ($this->status == false ? 0 : 1);
            $this->branchTypeCategory->save();

            $msg = 'Şube kategorisi güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Şube kategorisi güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
