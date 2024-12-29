<?php

namespace App\Livewire\User;

use App\Services\UserService;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class Users extends Component
{
    use LivewireAlert;

    protected UserService $userService;

    public null|string $data_id;

    public function render()
    {
        return view('livewire.user.users');
    }

    #[On('delete-user')]
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
    public function handleConfirmed(UserService $userService)
    {
        try {
            $userService->delete($this->data_id);
            $msg = 'Kullanıcı silindi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
        } catch (\Exception $exception) {
            $error = "Kullanıcı silinemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
        } finally {
            $this->dispatch('pg:eventRefresh-UserTable');
        }
    }
}
