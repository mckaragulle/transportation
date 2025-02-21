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

class VehicleModelCreate extends Component
{
    use LivewireAlert;

    public null|Collection $vehicleBrands;
    public null|Collection $vehicleTickets;
    public null|string|int $vehicle_brand_id = null;
    public null|string|int $vehicle_ticket_id = null;
    public null|string $name;
    public null|string $insurance_number;
    public bool $status = true;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'vehicle_brand_id' => ['required', 'integer', 'exists:vehicle_brands,id'],
        'vehicle_ticket_id' => ['required', 'integer', 'exists:vehicle_tickets,id'],
        'name' => ['required'],
        'insurance_number' => ['nullable'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'vehicle_brand_id.required' => 'Lütfen marka seçiniz.',
        'vehicle_brand_id.exists' => 'Lütfen geçerli bir marka seçiniz.',
        'vehicle_ticket_id.required' => 'Lütfen tip seçiniz.',
        'vehicle_ticket_id.exists' => 'Lütfen geçerli bir tip seçiniz.',
        'name.required' => 'Lütfen araç modelini yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.landlord.vehicle-model.vehicle-model-create');
    }

    public function mount(LandlordVehicleBrandService $vehicleBrandService)
    {
        $this->vehicleBrands = $vehicleBrandService->all();
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(LandlordVehicleModelService $vehicleModelService)
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $vehicleModel = $vehicleModelService->create([
                'vehicle_brand_id' => $this->vehicle_brand_id ?? null,
                'vehicle_ticket_id' => $this->vehicle_ticket_id ?? null,
                'name' => $this->name,
                'insurance_number' => $this->insurance_number??null,
                'status' => $this->status == false ? 0 : 1,
            ]);

            $this->dispatch('pg:eventRefresh-VehicleModelTable');
            $msg = 'Model oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset(['name','insurance_number']);
        } catch (\Exception $exception) {
            $error = "Model oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }

    public function updatedVehicleBrandId($value, $key)
    {
        $this->reset(['vehicle_ticket_id']);
        $this->getVehicleTickets();
    }

    public function getVehicleTickets(LandlordVehicleTicketService $vehicleTicketService = null)
    {
        $this->vehicleTickets = LandlordVehicleTicket::query()->select('id', 'name')->whereVehicleBrandId($this->vehicle_brand_id)->get();
    }
}
