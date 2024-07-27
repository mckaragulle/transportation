<?php

namespace App\Livewire\Permission;

use App\Services\PermissionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class PermissionCreate extends Component
{
    use LivewireAlert;

    public null|string $name;

    protected PermissionService $permissionService;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'name' => ['required'],
    ];

    protected $messages = [
        'name.required' => 'İzin adını yazınız.',
    ];

    public function render()
    {
        return view('livewire.permission.permission-create');
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(PermissionService $permissionService)
    {
        $this->validate();
        $this->permissionService = $permissionService;
        DB::beginTransaction();
        try {
            $this->permissionService->create([
                'name' => $this->name,
                'guard_name' => 'admin',
            ]);

            $this->dispatch('pg:eventRefresh-PermissionTable');
            $msg = 'Yeni izin oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset();
        } catch (\Exception $exception) {
            $error = "İzin oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
