<?php

namespace App\Livewire\Permission;

use App\Services\PermissionService;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class Permissions extends Component
{
    use LivewireAlert;

    protected PermissionService $permissionService;

    public null|int $data_id;

    public function alertMessage()
    {
        $this->alert('success', 'Basic Alert');
    }

    public function render()
    {
        return view('livewire.permission.permissions');
    }

    #[On('delete-permission')]
    function delete($id)
    {
        $this->data_id = $id;
        $this->confirm('Bu işlemi yapmak istediğinize emin misiniz?',
        [
            'onConfirmed' => 'handleConfirmed',
            'position' => 'center',
            'toast' => false,
            'confirmButtonText' => 'Evet',
            'cancelButtonText' => 'Hayır',
            'theme' => 'dark',
        ]);
    }

    #[On('handleConfirmed')]
    public function handleConfirmed(Permission $permission)
    {
        try {
            $permission->whereId($this->data_id)->delete();
            $msg = 'İzin silindi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
        } catch (\Exception $exception) {
            $error = "İzin silinemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
        } finally {
            $this->dispatch('pg:eventRefresh-PermissionTable');
        }
    }
}
