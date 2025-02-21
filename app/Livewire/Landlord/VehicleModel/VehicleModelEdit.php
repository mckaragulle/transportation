<?php

namespace App\Livewire\Landlord\VehicleModel;

use App\Models\Landlord\LandlordVehicleTicket;
use App\Services\Landlord\LandlordVehicleBrandService;
use App\Services\Landlord\LandlordVehicleModelService;
use App\Services\Landlord\LandlordVehicleTicketService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class VehicleModelEdit extends Component
{
    use LivewireAlert;

    public null|Collection $vehicleBrands;
    public null|Collection $vehicleTickets;

    public $vehicleModel;

    public ?int $vehicle_brand_id = null;
    public ?int $vehicle_ticket_id = null;
    public null|string $name;
    public null|string $insurance_number;
    public bool $status = true;

    protected LandlordVehicleBrandService $vehicleBrandService;
    protected LandlordVehicleTicketService $vehicleTicketService;

    /**
     * List of add/edit form rules
     */
    public function rules()
    {
        return [
            'vehicle_brand_id' => ['required', 'integer', 'exists:vehicle_brands,id'],
            'vehicle_ticket_id' => ['required', 'integer', 'exists:vehicle_tickets,id'],
            'name' => ['required'],
            'insurance_number' => ['nullable'],
            'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
        ];
    }

    protected $messages = [
        'vehicle_brand_id.required' => 'Lütfen marka seçiniz.',
        'vehicle_brand_id.exists' => 'Lütfen geçerli bir marka seçiniz.',
        'vehicle_ticket_id.required' => 'Lütfen tip seçiniz.',
        'vehicle_ticket_id.exists' => 'Lütfen geçerli bir tip seçiniz.',
        'name.required' => 'Lütfen araç modelini yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null,
                          LandlordVehicleBrandService $vehicleBrandService,
                          LandlordVehicleModelService $vehicleModelService
    )
    {
        if (!is_null($id)) {
            $this->vehicleModel = $vehicleModelService->findById($id);
            $this->vehicle_brand_id = $this->vehicleModel->vehicle_brand_id;
            $this->vehicle_ticket_id = $this->vehicleModel->vehicle_ticket_id;
            $this->name = $this->vehicleModel->name;
            $this->insurance_number = $this->vehicleModel->insurance_number;
            $this->status = $this->vehicleModel->status;
            $this->vehicleBrands = $vehicleBrandService->all();
            $this->getVehicleTickets();
        } else {
            return $this->redirect(route('vehicle_models.list'));
        }
    }

    public function render()
    {
        return view('livewire.landlord.vehicle-model.vehicle-model-edit');
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
            $this->vehicleModel->vehicle_brand_id = $this->vehicle_brand_id;
            $this->vehicleModel->vehicle_ticket_id = $this->vehicle_ticket_id;
            $this->vehicleModel->name = $this->name;
            $this->vehicleModel->insurance_number = $this->insurance_number;
            $this->vehicleModel->status = $this->status == false ? 0 : 1;
            $this->vehicleModel->save();

            $msg = 'Model güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Model güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }

    public function updatedVehicleBrandId($value, $key)
    {
        if(is_numeric($value))
        {
            $this->reset(['vehicle_ticket_id']);
            $this->getVehicleTickets();
        }
    }

    public function getVehicleTickets(LandlordVehicleTicketService $vehicleTicketService = null)
    {
        $this->vehicleTickets = LandlordVehicleTicket::query()->select('id', 'name')->whereVehicleBrandId($this->vehicle_brand_id)->get();
    }
}
