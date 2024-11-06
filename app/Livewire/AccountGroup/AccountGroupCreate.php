<?php

namespace App\Livewire\AccountGroup;

use App\Services\AccountGroupService;
use App\Services\AccountService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class AccountGroupCreate extends Component
{
    use LivewireAlert, WithFileUploads;

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

    public function render()
    {
        return view('livewire.account-group.account-group-create');
    }

    public function mount(AccountService $accountService)
    {
        $this->accounts = $accountService->all(['id', 'name']);
       
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(AccountGroupService $accountGroupService)
    {
        $this->validate();
        DB::beginTransaction();
        try {

            $account = $accountGroupService->create([
                'account_id' => $this->account_id ?? null,
                'group_id' => $this->group_id ?? null,
                'subgroup_id' => $this->subgroup_id ?? null,
                'sector_id' => $this->sector_id ?? null,
                'subsector_id' => $this->subsector_id ?? null,
                
            ]);

            $this->dispatch('pg:eventRefresh-AccountGroupTable');
            $msg = 'Cari grubu oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset();
        } catch (\Exception $exception) {
            $error = "Cari grubu oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}