<?php

namespace App\Livewire\Landlord\VehicleTicket;

use App\Services\VehicleBrandService;
use App\Services\VehicleTicketService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class VehicleTicketEdit extends Component
{
    use LivewireAlert;

    public null|Collection $vehicleBrands;

    public $vehicleTicket;

    public null|int $vehicle_brand_id;
    public null|string $name;
    public bool $status = true;

    protected VehicleBrandService $vehicleBrandService;
    /**
     * List of add/edit form rules
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
            ],
            'vehicle_brand_id' => [
                'vehicle_brand_id' => ['required', 'exists:vehicle_brands,id'],
                'exists:vehicle_brands,id',
            ],
            'status' => [
                'in:true,false,null,0,1,active,passive,',
                'nullable',
            ],
        ];
    }

    protected $messages = [
        'vehicle_brand_id.required' => 'Lütfen marka seçiniz.',
        'vehicle_brand_id.exists' => 'Lütfen geçerli bir marka seçiniz.',
        'name.required' => 'Tip adını yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null, VehicleBrandService $vehicleBrandService, VehicleTicketService $vehicleTicketService)
    {
        if (!is_null($id)) {
            $this->vehicleTicket = $vehicleTicketService->findById($id);
            $this->vehicle_brand_id = $this->vehicleTicket->vehicle_brand_id;
            $this->name = $this->vehicleTicket->name;
            $this->status = $this->vehicleTicket->status;
            $this->vehicleBrands = $vehicleBrandService->all();
        } else {
            return $this->redirect(route('vehicle_tickets.list'));
        }
    }

    public function render()
    {
        return view('livewire.landlord.vehicle-ticket.vehicle-ticket-edit');
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
            $this->vehicleTicket->vehicle_brand_id = $this->vehicle_brand_id;
            $this->vehicleTicket->name = $this->name;
            $this->vehicleTicket->status = $this->status == false ? 0 : 1;
            $this->vehicleTicket->save();

            $msg = 'Tip güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Tip güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
