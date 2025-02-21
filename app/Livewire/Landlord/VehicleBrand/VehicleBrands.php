<?php

namespace App\Livewire\Landlord\VehicleBrand;

use App\Services\VehicleBrandService;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class VehicleBrands extends Component
{
    use LivewireAlert;

    protected VehicleBrandService $vehicleBrandService;

    public null|string $data_id;


    public function render()
    {
        return view('livewire.landlord.vehicle-brand.vehicle-brands');
    }

    #[On('delete-vehicleBrand')]
    function delete($id)
    {
        $this->data_id = $id;
        $this->confirm(
            'Bu işlemi yapmak istediğinize emin misiniz?',
            [
                'onConfirmed' => 'handleConfirmed',
                'position' => 'center',
                'toast' => false,
                'confirmButtonText' => 'Evet',
                'cancelButtonText' => 'Hayır',
                'theme' => 'dark',
            ]
        );
    }

    #[On('handleConfirmed')]
    public function handleConfirmed(VehicleBrandService $vehicleBrandService)
    {
        try {
            $vehicleBrandService->delete($this->data_id);
            $msg = 'Araç markası silindi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
        } catch (\Exception $exception) {
            $error = "Araç markası silinemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
        } finally {
            $this->dispatch('pg:eventRefresh-VehicleBrandTable');
        }
    }
}
