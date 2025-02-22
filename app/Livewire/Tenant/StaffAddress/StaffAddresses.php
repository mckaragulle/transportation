<?php

namespace App\Livewire\Tenant\StaffAddress;

use App\Services\Tenant\StaffService;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class StaffAddresses extends Component
{
    use LivewireAlert;

    public null|string $staff_id = null;
    public null|string $data_id;

    public bool $is_show = false;

    public function mount(null|string $id = null, null|bool $is_show)
    {
        $this->staff_id = $id;
        $this->is_show = $is_show;
    }

    public function render()
    {
        return view('livewire.tenant.staff-address.staff-addresses');
    }

    #[On('delete-staff-address')]
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
    public function handleConfirmed(StaffService $staffAddressService)
    {
        try {
            $staffAddressService->delete($this->data_id);
            $msg = 'Personel adresi silindi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
        } catch (\Exception $exception) {
            $error = "Personel adresi silinemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
        } finally {
            $this->dispatch('pg:eventRefresh-StaffAddressTable');
        }
    }
}
