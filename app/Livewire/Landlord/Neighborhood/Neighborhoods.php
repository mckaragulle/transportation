<?php

namespace App\Livewire\Landlord\Neighborhood;

use App\Services\Landlord\LandlordNeighborhoodService;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class Neighborhoods extends Component
{
    use LivewireAlert;

    public null|string $data_id;


    public function render()
    {
        return view('livewire.landlord.neighborhood.neighborhoods');
    }

    #[On('delete-neighborhood')]
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
    public function handleConfirmed(LandlordNeighborhoodService $neighborhoodService)
    {
        try {
            $neighborhoodService->delete($this->data_id);
            $msg = 'Mahalle silindi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
        } catch (\Exception $exception) {
            $error = "Mahalle silinemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
        } finally {
            $this->dispatch('pg:eventRefresh-NeighborhoodTable');
        }
    }
}
