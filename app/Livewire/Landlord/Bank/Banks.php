<?php

namespace App\Livewire\Landlord\Bank;

use App\Services\Landlord\LandlordBankService;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class Banks extends Component
{
    use LivewireAlert;

    public null|string $data_id;


    public function render()
    {
        return view('livewire.landlord.bank.banks');
    }

    #[On('delete-bank')]
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
    public function handleConfirmed(LandlordBankService $bankService)
    {
        try {
            $bankService->delete($this->data_id);
            $msg = 'Banka silindi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
        } catch (\Exception $exception) {
            $error = "Banka silinemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
        } finally {
            $this->dispatch('pg:eventRefresh-BankTable');
        }
    }
}
