<?php

namespace App\Livewire\VehicleProperty;

use App\Models\VehicleProperty;
use App\Services\VehiclePropertyCategoryService;
use App\Services\VehiclePropertyService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class VehiclePropertyEdit extends Component
{
    use LivewireAlert;

    public null|Collection $vehiclePropertyCategories;

    public ?VehicleProperty $vehicleProperty = null;

    public null|int $vehicle_property_category_id = null;
    public null|int $vehicle_property_id = null;
    public null|string $name;
    public bool $status = true;

    protected VehiclePropertyCategoryService $vehicleBrandService;
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

    public function mount($id = null, VehiclePropertyCategoryService $vehiclePropertyCategoryService, VehiclePropertyService $vehiclePropertyService)
    {
        if (!is_null($id)) {
            $this->vehicleProperty = $vehiclePropertyService->findById($id);
            $this->vehicle_property_category_id = $this->vehicleProperty->vehicle_property_category_id;
            $this->vehicle_property_id = $this->vehicleProperty->vehicle_property_id??null;
            $this->name = $this->vehicleProperty->name;
            $this->status = $this->vehicleProperty->status;
            $this->vehiclePropertyCategories = $vehiclePropertyCategoryService->all();
        } else {
            return $this->redirect(route('vehicleProperties.list'));
        }
    }

    public function render()
    {
        return view('livewire.vehicle-property.vehicle-property-edit');
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
            $this->vehicleProperty->vehicle_property_id = $this->vehicle_property_id;
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
}
