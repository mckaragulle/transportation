<?php

namespace App\Livewire\StaffTypeCategory;

use App\Models\StaffTypeCategory;
use App\Services\StaffTypeCategoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class StaffTypeCategoryEdit extends Component
{
    use LivewireAlert;

    public null|StaffTypeCategory $staffTypeCategory;

    public null|string $name;

    public bool $status = true;

    protected StaffTypeCategoryService $staffTypeCategoryService;
    /**
     * List of add/edit form rules
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
            ],
            'status' => [
                'in:true,false,null,0,1,active,passive,',
                'nullable',
            ],
        ];
    }

    protected $messages = [
        'name.required' => 'Personel kategorisi yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null, StaffTypeCategoryService $staffTypeCategoryService)
    {
        if (!is_null($id)) {

            $this->staffTypeCategory = $staffTypeCategoryService->findById($id);
            $this->name = $this->staffTypeCategory->name;
            $this->status = $this->staffTypeCategory->status;
        } else {
            return $this->redirect(route('staff_type_categories.list'));
        }
    }

    public function render()
    {
        return view('livewire.staff-type-category.staff-type-category-edit');
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
            $this->staffTypeCategory->name = $this->name;
            $this->staffTypeCategory->status = ($this->status == false ? 0 : 1);
            $this->staffTypeCategory->save();

            $msg = 'Personel kategorisi güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Personel kategorisi güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
