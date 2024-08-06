<?php

namespace App\Livewire\VehicleTicket;

use App\Services\VehicleBrandService;
use App\Services\VehicleTicketService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class VehicleTicketCreate extends Component
{
    use LivewireAlert;

    public null|Collection $vehicleBrands;
    public null|int $vehicle_brand_id = null;
    public null|string $name;
    public null|string|float $price;

    public bool $is_default = true;
    public bool $status = true;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'vehicle_brand_id' => ['required', 'exists:vehicle_brands,id'],
        'name' => ['required'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'vehicle_brand_id.required' => 'Lütfen marka seçiniz.',
        'vehicle_brand_id.exists' => 'Lütfen geçerli bir marka seçiniz.',
        'name.required' => 'Tip adını yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.vehicle-ticket.vehicle-ticket-create');
    }

    public function mount(VehicleBrandService $vehicleBrandService)
    {
        $this->vehicleBrands = $vehicleBrandService->all();
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(VehicleTicketService $vehicleTicketService)
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $vehicleTicket = $vehicleTicketService->create([
                'vehicle_brand_id' => $this->vehicle_brand_id ?? null,
                'name' => $this->name,
                'status' => $this->status == false ? 0 : 1,
            ]);

            $this->dispatch('pg:eventRefresh-VehicleTicketTable');
            $msg = 'Tip oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset();
        } catch (\Exception $exception) {
            $error = "Tip oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
