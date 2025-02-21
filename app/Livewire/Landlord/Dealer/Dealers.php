<?php

namespace App\Livewire\Landlord\Dealer;

use App\Services\Landlord\LandlordDealerService;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class Dealers extends Component
{
    use LivewireAlert;

    protected LandlordDealerService $dealerService;

    public null|string $data_id;


    public function render()
    {
        return view('livewire.landlord.dealer.dealers');
    }

    #[On('delete-dealer')]
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
    public function handleConfirmed(LandlordDealerService $dealerService)
    {
        try {
            $dealerService->delete($this->data_id);
            $msg = 'Bayi silindi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
        } catch (\Exception $exception) {
            $error = "Bayi silinemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
        } finally {
            $this->dispatch('pg:eventRefresh-DealerTable');
        }
    }
}
