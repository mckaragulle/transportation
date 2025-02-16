<?php

namespace App\Livewire\Landlord\Sector;

use App\Services\Landlord\LandlordSectorService;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class Sectors extends Component
{
    use LivewireAlert;

    public null|string $data_id;


    public function render()
    {
        return view('livewire.landlord.sector.sectors');
    }

    #[On('delete-sector')]
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
    public function handleConfirmed(LandlordSectorService $sectorService)
    {
        try {
            $sectorService->delete($this->data_id);
            $msg = 'Cari sektörü silindi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
        } catch (\Exception $exception) {
            $error = "Cari sektörü silinemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
        } finally {
            $this->dispatch('pg:eventRefresh-SectorTable');
        }
    }
}
