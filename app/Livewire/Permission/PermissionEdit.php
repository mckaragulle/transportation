<?php

namespace App\Livewire\Permission;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class PermissionEdit extends Component
{
    use LivewireAlert;

    public null|Permission $permission;
    public null|string $name;

    /**
     * List of add/edit form rules
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                Rule::unique('permissions')->ignore($this->permission),
            ]
        ];
    }

    protected $messages = [
        'name.required' => 'İzin adını yazınız.',
    ];

    public function mount($id = null, Permission $permission)
    {
        if(!is_null($id)) {
            $this->permission = $permission->whereId($id)->first();
            $this->name = $this->permission->name;
        }
        else{
            return $this->redirect(route('permissions.list'));
        }
    }

    public function render()
    {
        return view('livewire.permission.permission-edit');
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
            $this->permission->name = $this->name;
            $this->permission->save();

            $msg = 'İzin güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "İzin güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
