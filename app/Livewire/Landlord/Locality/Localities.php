<?php

namespace App\Livewire\Landlord\Locality;

use App\Services\Landlord\LandlordLocalityService;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class Localities extends Component
{
    use LivewireAlert;

    public null|string $data_id;


    public function render()
    {
        return view('livewire.landlord.locality.localities');
    }

    #[On('delete-locality')]
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
    public function handleConfirmed(LandlordLocalityService $localityService)
    {
        try {
            $localityService->delete($this->data_id);
            $msg = 'Semt silindi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
        } catch (\Exception $exception) {
            $error = "Semt silinemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
        } finally {
            $this->dispatch('pg:eventRefresh-LocalityTable');
        }
    }
}
