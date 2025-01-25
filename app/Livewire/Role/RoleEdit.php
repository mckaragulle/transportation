<?php

namespace App\Livewire\Role;

use App\Services\RoleService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleEdit extends Component
{
    use LivewireAlert;

    public null|Role $role;
    public null|string $name;
    public null|array|Collection $permissions;
    public null|array $permission;
    public null|array $p;

    /**
     * List of add/edit form rules
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                Rule::unique('roles')->ignore($this->role),
            ]
        ];
    }

    protected $messages = [
        'name.required' => 'Rol adını yazınız.',
    ];

    public function mount($id = null, Role $role)
    {
        if(!is_null($id)) {
            $this->role = $role->whereId($id)->first();
            $this->name = $this->role->name;
            $this->permissions = Permission::where('guard_name', 'admin')->get();
            $this->permission = $this->role->permissions->pluck('name')->toArray();
        }
        else{
            return $this->redirect(route('roles.list'));
        }
    }

    public function render()
    {
        return view('livewire.role.role-edit');
    }

    /**
     * update the exam data
     *
     * @return void
     */
    public function update()
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $this->role->name = $this->name;
            $this->role->save();

            $this->role->syncPermissions($this->permission);

            $msg = 'Rol güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Rol güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
