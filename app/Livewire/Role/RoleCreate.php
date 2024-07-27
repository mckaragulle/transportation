<?php

namespace App\Livewire\Role;

use App\Services\RoleService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class RoleCreate extends Component
{
    use LivewireAlert;

    public null|string $guard_name;
    public null|string $name;
    public null|array|Collection $roles;
    public null|array|Collection $permissions;

    public null|array $permission;


    protected RoleService $roleService;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'name' => ['required'],
    ];

    protected $messages = [
        'name.required' => 'Rol adını yazınız.',
    ];

    public function mount(RoleService $roleService)
    {
        $this->roles = $roleService->all();
        $role = $roleService->findById(1);
        $this->permissions = $role->permissions;
    }

    public function render()
    {
        return view('livewire.role.role-create');
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(RoleService $roleService)
    {
        $this->validate();
        $this->roleService = $roleService;
        DB::beginTransaction();
        try {
            $role = $this->roleService->create([
                'name' => $this->name,
                'guard_name' => 'admin',
            ]);

            $role->syncPermissions($this->permission);

            $this->dispatch('pg:eventRefresh-RoleTable');
            $msg = 'Yeni rol oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset();
        } catch (\Exception $exception) {
            $error = "Rol oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
        $this->dispatch('update-permission');
    }
}
