<?php

namespace App\Livewire\VehicleProperty;

use App\Models\VehicleProperty;
use App\Models\VehiclePropertyCategory;
use App\Services\VehiclePropertyCategoryService;
use App\Services\VehiclePropertyService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class VehiclePropertyCreate extends Component
{
    use LivewireAlert;

    public null|Collection $vehiclePropertyCategories;
    public null|Collection $vehicleProperties;
    public null|int $vehicle_property_category_id = null;
    public null|int $vehicle_property_id = null;
    public null|string $name;

    public bool $status = true;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'vehicle_property_category_id' => ['required', 'exists:vehicle_property_categories,id'],
        'vehicle_property_id' => ['nullable', 'exists:vehicle_properties,id'],
        'name' => ['required'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'vehicle_property_category_id.required' => 'Lütfen özellik kategorisini seçiniz.',
        'vehicle_property_category_id.exists' => 'Lütfen geçerli bir özellik kategorisi seçiniz.',
        'vehicle_property_id.exists' => 'Lütfen geçerli bir özellik seçiniz.',
        'name.required' => 'Özellik adını yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.vehicle-property.vehicle-property-create');
    }

    public function mount(VehiclePropertyCategoryService $vehiclePropertyCategoryService)
    {
        $this->vehiclePropertyCategories = $vehiclePropertyCategoryService->all(['id', 'name']);
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(VehiclePropertyService $vehiclePropertyService)
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $vehicleProperty = $vehiclePropertyService->create([
                'vehicle_property_category_id' => $this->vehicle_property_category_id ?? null,
                'vehicle_property_id' => $this->vehicle_property_id ?? null,
                'name' => $this->name,
                'status' => $this->status == false ? 0 : 1,
            ]);

            $this->dispatch('pg:eventRefresh-VehiclePropertyTable');
            $msg = 'Özellik oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset(['name']);
        } catch (\Exception $exception) {
            $error = "Özellik oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }

    public function updatedVehiclePropertyCategoryId()
    {
        $this->vehicleProperties = VehicleProperty::query()->where(['vehicle_property_category_id' => $this->vehicle_property_category_id])->whereNull('vehicle_property_id')->get(['id', 'name']);
    }
}
