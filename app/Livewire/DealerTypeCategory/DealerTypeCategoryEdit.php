<?php

namespace App\Livewire\DealerTypeCategory;

use App\Models\DealerTypeCategory;
use App\Services\DealerTypeCategoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class DealerTypeCategoryEdit extends Component
{
    use LivewireAlert;

    public null|DealerTypeCategory $dealerTypeCategory;

    public null|string $name;

    public bool $is_required = true;
    public bool $is_multiple = false;
    public bool $status = true;

    protected DealerTypeCategoryService $dealerTypeCategoryService;
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

    public function mount($id = null, DealerTypeCategoryService $dealerTypeCategoryService)
    {
        if (!is_null($id)) {

            $this->dealerTypeCategory = $dealerTypeCategoryService->findById($id);
            $this->name = $this->dealerTypeCategory->name;
            $this->is_required = $this->dealerTypeCategory->is_required;
            $this->is_multiple = $this->dealerTypeCategory->is_multiple;
            $this->status = $this->dealerTypeCategory->status;
        } else {
            return $this->redirect(route('dealer_type_categories.list'));
        }
    }

    public function render()
    {
        return view('livewire.dealer-type-category.dealer-type-category-edit');
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
            $this->dealerTypeCategory->name = $this->name;
            $this->dealerTypeCategory->is_required = ($this->is_required == false ? 0 : 1);
            $this->dealerTypeCategory->is_multiple = ($this->is_multiple == false ? 0 : 1);
            $this->dealerTypeCategory->status = ($this->status == false ? 0 : 1);
            $this->dealerTypeCategory->save();

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
