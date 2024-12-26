<?php

namespace App\Livewire\DealerBank;

use App\Services\DealerBankService;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class DealerBanks extends Component
{
    use LivewireAlert;

    public null|int $data_id;

    public bool $is_show = false;
    public null|int $dealer_id = null;

    public function mount($id = null, bool $is_show)
    {
        $this->dealer_id = $id;
        $this->is_show = $is_show;
    }

    public function render()
    {
        return view('livewire.dealer-bank.dealer-banks');
    }

    #[On('delete-dealer-bank')]
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
    public function handleConfirmed(DealerBankService $dealerBankService)
    {
        try {
            $dealerBankService->delete($this->data_id);
            $msg = 'Bayi banka bilgisi silindi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
        } catch (\Exception $exception) {
            $error = "Bayi banka bilgisi silinemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
        } finally {
            $this->dispatch('pg:eventRefresh-DealerBankTable');
        }
    }
}
