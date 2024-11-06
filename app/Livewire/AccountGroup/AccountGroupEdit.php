<?php

namespace App\Livewire\AccountGroup;

use App\Models\AccountGroup;
use App\Services\AccountGroupService;
use App\Services\AccountService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class AccountGroupEdit extends Component
{
    use LivewireAlert, WithFileUploads;

    public ?AccountGroup $accountGroup = null;
    public null|Collection $accounts = null;
    public null|int $account_id = null;
    public null|int $group_id = null;
    public null|int $subgroup_id = null;
    public null|int $sector_id = null;
    public null|int $subsector_id = null;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'account_id' => ['required', 'exists:accounts,id'],
        'group_id' => ['required', 'exists:groups,id'],
        'subgroup_id' => ['required', 'exists:groups,id'],
        'sector_id' => ['required', 'exists:sectors,id'],
        'subsector_id' => ['required', 'exists:sectors,id'],
    ];

    protected $messages = [
        'account_id.required' => 'Lütfen cari seçiniz yazınız.',
        'account_id.exists' => 'Lütfen geçerli bir cari seçiniz yazınız.',
        'group_id.required' => 'Lütfen grup seçiniz yazınız.',
        'group_id.exists' => 'Lütfen geçerli bir grup seçiniz yazınız.',
        'subgroup_id.required' => 'Lütfen alt grup seçiniz yazınız.',
        'subgroup_id.exists' => 'Lütfen geçerli bir alt grup seçiniz yazınız.',
        'sector_id.required' => 'Lütfen sektör seçiniz yazınız.',
        'sector_id.exists' => 'Lütfen geçerli bir sektör seçiniz yazınız.',
        'subsector_id.required' => 'Lütfen alt sektör seçiniz yazınız.',
        'subsector_id.exists' => 'Lütfen geçerli bir alt sektör seçiniz yazınız.',
    ];

    public function mount($id = null, AccountService $accountService, AccountGroupService $accountGroupService)
    {
        if (!is_null($id)) {
            $this->accountGroup = $accountGroupService->findById($id);
            $this->accounts = $accountService->all(['id', 'name']);

            $this->account_id = $this->accountGroup->account_id;
            $this->group_id = $this->accountGroup->group_id;
            $this->subgroup_id = $this->accountGroup->subgroup_id;
            $this->sector_id = $this->accountGroup->sector_id;
            $this->subsector_id = $this->accountGroup->subsector_id;
            
        } else {
            return $this->redirect(route('account_groups.list'));
        }
    }

    public function render()
    {
        return view('livewire.account-group.account-group-edit');
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
            
            $this->accountGroup->account_id = $this->account_id;
            $this->accountGroup->group_id = $this->group_id;
            $this->accountGroup->subgroup_id = $this->subgroup_id;
            $this->accountGroup->sector_id = $this->sector_id;
            $this->accountGroup->subsector_id = $this->subsector_id;
           
            $this->accountGroup->save();
            
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