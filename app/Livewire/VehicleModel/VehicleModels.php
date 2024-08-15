<?php

namespace App\Livewire\VehicleModel;

use App\Services\VehicleModelService;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class VehicleModels extends Component
{
    use LivewireAlert;

    public null|int $data_id;

    public function render()
    {
        return view('livewire.vehicle-model.vehicle-models');
    }

    #[On('delete-vehicleTicket')]
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
    public function handleConfirmed(VehicleModelService $vehicleModelService)
    {
        try {
            $vehicleModelService->delete($this->data_id);
            $msg = 'Araç modeli silindi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
        } catch (\Exception $exception) {
            $error = "Araç modeli silinemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
        } finally {
            $this->dispatch('pg:eventRefresh-VehicleModelTable');
        }
    }
}