<?php

namespace App\Livewire\Landlord\VehicleModel;

use App\Models\Tenant\VehicleTicket;
use App\Services\VehicleBrandService;
use App\Services\VehicleModelService;
use App\Services\VehicleTicketService;
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

    protected VehicleBrandService $vehicleBrandService;
    protected VehicleTicketService $vehicleTicketService;
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

    public function mount($id = null, VehicleBrandService $vehicleBrandService, VehicleModelService $vehicleModelService)
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

    public function getVehicleTickets(VehicleTicketService $vehicleTicketService = null)
    {
        $this->vehicleTickets = VehicleTicket::query()->select('id', 'name')->whereVehicleBrandId($this->vehicle_brand_id)->get();
    }
}
