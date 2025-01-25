<?php

namespace App\Livewire\VehiclePropertyCategory;

use App\Models\VehiclePropertyCategory;
use App\Services\VehiclePropertyCategoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class VehiclePropertyCategoryEdit extends Component
{
    use LivewireAlert;

    public null|VehiclePropertyCategory $vehiclePropertyCategory;

    public null|string $name;

    public bool $status = true;

    protected VehiclePropertyCategoryService $vehiclePropertyCategoryService;
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
        'name.required' => 'Araç özellik kategorisi yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null, VehiclePropertyCategoryService $vehiclePropertyCategoryService)
    {
        if (!is_null($id)) {

            $this->vehiclePropertyCategory = $vehiclePropertyCategoryService->findById($id);
            $this->name = $this->vehiclePropertyCategory->name;
            $this->status = $this->vehiclePropertyCategory->status;
        } else {
            return $this->redirect(route('vehiclePropertyCategorys.list'));
        }
    }

    public function render()
    {
        return view('livewire.vehicle-property-category.vehicle-property-category-edit');
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
            $this->vehiclePropertyCategory->name = $this->name;
            $this->vehiclePropertyCategory->status = ($this->status == false ? 0 : 1);
            $this->vehiclePropertyCategory->save();

            $msg = 'Araç özellik kategorisi güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Araç özellik kategorisi güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
