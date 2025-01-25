<?php

namespace App\Livewire\Group;

use App\Models\Group;
use App\Services\GroupService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class GroupCreate extends Component
{
    use LivewireAlert;
    public null|Collection $groups;
    public null|string|int $group_id = null;
    public null|string $name;

    public bool $status = true;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'group_id' => ['nullable', 'exists:groups,id'],
        'name' => ['required'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'group_id.exists' => 'Lütfen geçerli bir grup seçiniz.',
        'name.required' => 'Cari grubu adını yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.group.group-create');
    }

    public function mount()
    {
        $this->groups = Group::query()
            ->whereNull('group_id')
            ->orderBy('id')
            ->get(['id', 'group_id', 'name']);
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(GroupService $groupService)
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $group = $groupService->create([
                'group_id' => $this->group_id ?? null,
                'name' => $this->name,
                'status' => $this->status == false ? 0 : 1,
            ]);

            $this->dispatch('pg:eventRefresh-GroupTable');
            $msg = 'Cari grubu oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            
            $this->groups = Group::query()
            ->whereNull('group_id')
            ->orderBy('id')
            ->get(['id', 'group_id', 'name']);
            
            DB::commit();
            $this->reset(['name', 'group_id']);
            
        } catch (\Exception $exception) {
            $error = "Cari grubu oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
