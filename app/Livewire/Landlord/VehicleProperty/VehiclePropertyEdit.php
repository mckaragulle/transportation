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

class VehiclePropertyEdit extends Component
{
    use LivewireAlert;

    public null|Collection $vehiclePropertyCategories;
    public null|Collection $vehicleProperties;

    public ?LandlordVehicleProperty $vehicleProperty = null;

    public null|int $vehicle_property_category_id = null;
    public null|int $vehicle_property_id = null;
    public null|string $name;
    public bool $status = true;

    protected LandlordVehiclePropertyCategoryService $vehiclePropertyCategoryService;
    protected LandlordVehiclePropertyService $vehiclePropertyService;
    /**
     * List of add/edit form rules
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
            ],
            'vehicle_property_category_id' => [
                'required',
                'exists:vehicle_property_categories,id',
            ],
            'vehicle_property_id' => [
                'nullable',
                'exists:vehicle_properties,id',
            ],
            'status' => [
                'in:true,false,null,0,1,active,passive,',
                'nullable',
            ],
        ];
    }

    protected $messages = [
        'vehicle_property_category_id.required' => 'Lütfen özellik kategorisini seçiniz.',
        'vehicle_property_category_id.exists' => 'Lütfen geçerli bir özellik kategorisi seçiniz.',
        'vehicle_property_id.exists' => 'Lütfen geçerli bir özellik seçiniz.',
        'name.required' => 'Özellik adını yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null, LandlordVehiclePropertyCategoryService $vehiclePropertyCategoryService, LandlordVehiclePropertyService $vehiclePropertyService)
    {
        if (!is_null($id)) {
            $this->vehicleProperty = $vehiclePropertyService->findById($id);
            $this->vehicle_property_category_id = $this->vehicleProperty->vehicle_property_category_id;
            $this->vehicle_property_id = $this->vehicleProperty->vehicle_property_id??null;
            $this->name = $this->vehicleProperty->name;
            $this->status = $this->vehicleProperty->status;
            $this->vehiclePropertyCategories = $vehiclePropertyCategoryService->all();
            $this->vehicleProperties = LandlordVehicleProperty::query()->where(['vehicle_property_category_id' => $this->vehicle_property_category_id])->with('vehicle_property')->orderBy('id')->get(['id', 'vehicle_property_id', 'name']);

        } else {
            return $this->redirect(route('vehicle_properties.list'));
        }
    }

    public function render()
    {
        return view('livewire.landlord.vehicle-property.vehicle-property-edit');
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
            $this->vehicleProperty->vehicle_property_category_id = $this->vehicle_property_category_id;
            $this->vehicleProperty->vehicle_property_id = $this->vehicle_property_id ?? null;
            $this->vehicleProperty->name = $this->name;
            $this->vehicleProperty->status = $this->status == false ? 0 : 1;
            $this->vehicleProperty->save();

            $msg = 'Özellik güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Özellik güncellenemedi. {$exception->getMessage()}";
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
