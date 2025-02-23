<?php

namespace App\Livewire\Tenant\StaffBank;

use App\Services\Tenant\StaffBankService;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class StaffBanks extends Component
{
    use LivewireAlert;

    public null|string $staff_id = null;
    public null|string $data_id;

    public bool $is_show = false;

    public function mount($id = null, bool $is_show)
    {
        $this->staff_id = $id;
        $this->is_show = $is_show;
    }

    public function render()
    {
        return view('livewire.tenant.staff-bank.staff-banks');
    }

    #[On('delete-staff-bank')]
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
    public function handleConfirmed(StaffBankService $staffBankService)
    {
        try {
            $staffBankService->delete($this->data_id);
            $msg = 'Personel banka bilgisi silindi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
        } catch (\Exception $exception) {
            $error = "Personel banka bilgisi silinemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
        } finally {
            $this->dispatch('pg:eventRefresh-StaffBankTable');
        }
    }
}
