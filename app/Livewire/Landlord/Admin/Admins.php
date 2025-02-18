<?php

namespace App\Livewire\Landlord\Admin;

use App\Services\Landlord\AdminService;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class Admins extends Component
{
    use LivewireAlert;

    protected AdminService $adminService;

    public null|string $data_id;

    public function render()
    {
        return view('livewire.landlord.admin.admins');
    }

    #[On('delete-admin')]
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
    public function handleConfirmed(AdminService $adminService)
    {
        try {
            $adminService->delete($this->data_id);
            $msg = 'Yönetici silindi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
        } catch (\Exception $exception) {
            $error = "Yönetici silinemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
        } finally {
            $this->dispatch('pg:eventRefresh-AdminTable');
        }
    }
}
