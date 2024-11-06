<?php

namespace App\Livewire\Group;

use App\Models\Group;
use App\Services\GroupService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class GroupEdit extends Component
{
    use LivewireAlert;

    public null|Collection $groups;

    public ?Group $group = null;

    public null|int $group_id = null;
    public null|string $name;
    public bool $status = true;

    protected GroupService $groupService;
    /**
     * List of add/edit form rules
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
            ],
            'group_id' => [
                'nullable',
                'exists:groups,id',
            ],
            'status' => [
                'in:true,false,null,0,1,active,passive,',
                'nullable',
            ],
        ];
    }

    protected $messages = [
        'group_id.exists' => 'Lütfen geçerli bir cari grubu seçiniz.',
        'name.required' => 'Cari grubu adını yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null, GroupService $groupService)
    {
        if (!is_null($id)) {
            $this->group = $groupService->findById($id);
            $this->group_id = $this->group->group_id??null;
            $this->name = $this->group->name??null;
            $this->status = $this->group->status;

            $this->groups = Group::query()
            ->whereNull('group_id')
            ->orderBy('id')
            ->get(['id', 'group_id', 'name']);

        } else {
            return $this->redirect(route('groups.list'));
        }
    }

    public function render()
    {
        return view('livewire.group.group-edit');
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
            $this->group->group_id = $this->group_id ?? null;
            $this->group->name = $this->name;
            $this->group->status = $this->status == false ? 0 : 1;
            $this->group->save();

            $msg = 'Cari grubu güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Cari grubu güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
