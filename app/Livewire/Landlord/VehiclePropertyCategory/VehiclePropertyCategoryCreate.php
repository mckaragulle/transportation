<?php

namespace App\Livewire\Landlord\VehiclePropertyCategory;

use App\Services\Landlord\LandlordVehiclePropertyCategoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class VehiclePropertyCategoryCreate extends Component
{
    use LivewireAlert;

    public null|string $name;

    public bool $status = true;

    protected LandlordVehiclePropertyCategoryService $vehiclePropertyCategoryService;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'name' => ['required'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'name.required' => 'Araç özellik kategorisi yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.landlord.vehicle-property-category.vehicle-property-category-create');
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(LandlordVehiclePropertyCategoryService $vehiclePropertyCategoryService)
    {
        $this->validate();
        $this->vehiclePropertyCategoryService = $vehiclePropertyCategoryService;
        DB::beginTransaction();
        try {
            $vehiclePropertyCategory = $this->vehiclePropertyCategoryService->create([
                'name' => $this->name,
                'status' => $this->status == false ? 0 : 1,
            ]);

            $this->dispatch('pg:eventRefresh-VehiclePropertyCategoryTable');
            $msg = 'Araç özellik kategorisi oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset('name');
        } catch (\Exception $exception) {
            $error = "Araç özellik kategorisi oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
