<?php

namespace App\Livewire\Landlord\VehicleProperty;

use App\Models\Landlord\LandlordVehicleProperty;
use App\Services\Landlord\LandlordVehiclePropertyCategoryService;
use App\Services\Landlord\LandlordVehiclePropertyService;
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
    public null|string|int $vehicle_property_category_id = null;
    public null|string|int $vehicle_property_id = null;
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
        return view('livewire.landlord.vehicle-property.vehicle-property-create');
    }

    public function mount(LandlordVehiclePropertyCategoryService $vehiclePropertyCategoryService)
    {
        $this->vehiclePropertyCategories = $vehiclePropertyCategoryService->all(['id', 'name']);
        $this->vehicleProperties = LandlordVehicleProperty::query()->where(['vehicle_property_category_id' => $this->vehicle_property_category_id])->with('vehicle_property')->orderBy('id')->get(['id', 'vehicle_property_id', 'name']);
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(LandlordVehiclePropertyService $vehiclePropertyService)
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
        $this->vehicleProperties = LandlordVehicleProperty::query()->where(['vehicle_property_category_id' => $this->vehicle_property_category_id])->with('vehicle_property')->orderBy('id')->get(['id', 'vehicle_property_id', 'name']);
    }
}
