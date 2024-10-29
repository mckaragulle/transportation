<?php

namespace App\Livewire\Neighborhood;

use App\Services\NeighborhoodService;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class Neighborhoods extends Component
{
    use LivewireAlert;

    public null|int $data_id;


    public function render()
    {
        return view('livewire.neighborhood.neighborhoods');
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
    public function handleConfirmed(NeighborhoodService $neighborhoodService)
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
