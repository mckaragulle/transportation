<?php

namespace App\Livewire\AccountAddress;

use App\Services\AccountService;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class AccountAddresses extends Component
{
    use LivewireAlert;

    public null|int $account_id = null;
    public null|int $data_id;

    public bool $is_show = false;

    public function mount(null|string $id = null, null|bool $is_show)
    {
        $this->account_id = $id;
        $this->is_show = $is_show;
    }

    public function render()
    {
        return view('livewire.account-address.account-addresses');
    }

    #[On('delete-account-address')]
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
    public function handleConfirmed(AccountService $accountAddressService)
    {
        try {
            $accountAddressService->delete($this->data_id);
            $msg = 'Cari adresi silindi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
        } catch (\Exception $exception) {
            $error = "Cari adresi silinemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
        } finally {
            $this->dispatch('pg:eventRefresh-AccountAddressTable');
        }
    }
}
