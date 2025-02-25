<?php

namespace App\Livewire\Landlord\DealerAddress;

use App\Services\DealerAddressService;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class DealerAddresses extends Component
{
    use LivewireAlert;

    public null|string $dealer_id = null;
    public null|string $data_id;

    public bool $is_show = false;

    public function mount(null|string $id = null, null|bool $is_show)
    {
        $this->dealer_id = $id;
        $this->is_show = $is_show;
    }

    public function render()
    {
        return view('livewire.landlord.dealer-address.dealer-addresses');
    }

    #[On('delete-dealer-address')]
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
    public function handleConfirmed(DealerAddressService $dealerAddressService)
    {
        try {
            $dealerAddressService->delete($this->data_id);
            $msg = 'Bayi adresi silindi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
        } catch (\Exception $exception) {
            $error = "Bayi adresi silinemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
        } finally {
            $this->dispatch('pg:eventRefresh-DealerAddressTable');
        }
    }
}
