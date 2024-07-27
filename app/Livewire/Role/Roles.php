<?php

namespace App\Livewire\Role;

use App\Services\RoleService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Roles extends Component
{
    use LivewireAlert;

    public null|Role $role;

    protected RoleService $roleService;

    public null|int $data_id;

    public function render()
    {
        return view('livewire.role.roles');
    }

    #[On('delete-role')]
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
    public function handleConfirmed(Role $role)
    {
        DB::beginTransaction();
        try {
            $this->role = $role;
            $role = $this->role->whereId($this->data_id)->first();
            $role->syncPermissions([]);
            $role->delete();
            $msg = 'Rol silindi.';
            session()->flash('message', $msg);
            DB::commit();
            $this->alert('success', $msg, ['position' => 'center']);
        } catch (\Exception $exception) {
            $error = "Rol silinemedi. {$exception->getMessage()}";
            DB::rollBack();
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
        } finally {
            $this->dispatch('pg:eventRefresh-RoleTable');
        }
    }
}
