<?php

namespace App\Livewire\Tenant\Branch;

use App\Models\Tenant\BranchTypeCategoryBranchTypeBranch;
use App\Services\Tenant\BranchService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class BranchCreate extends Component
{
    use LivewireAlert;

    public null|array $branch_type_categories = [];
    public null|Collection $branchTypeCategoryDatas;
    public null|Collection $accounts = null;
    public null|Collection $cities = null;
    public null|Collection $districts = null;
    
    public null|string $account_id = null;
    public null|string $city_id = null;
    public null|string $district_id = null;
    public null|string $name;
    public null|string $phone;
    public null|string $email;

    public bool $status = true;

    protected BranchService $branchService;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'branch_type_categories' => ['nullable', 'array'],
        'branch_type_categories.*' => ['nullable'],
        'account_id' => ['required', 'exists:tenant.branches,id'],
        'city_id' => ['required', 'exists:tenant.cities,id'],
        'district_id' => ['required', 'exists:tenant.districts,id'],
        'name' => ['required'],
        'phone' => ['nullable', ],
        'email' => ['required', 'email', 'unique:tenant.branchs,email'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'branch_type_categories.required' => 'Lütfen şube kategorisini seçiniz.',
        'branch_type_categories.array' => 'Lütfen geçerli bir şube kategorisi seçiniz.',
        'account_id.required' => 'Lütfen cari seçiniz yazınız.',
        'account_id.exists' => 'Lütfen geçerli bir cari seçiniz yazınız.',
        'city_id.required' => 'Lütfen şehir seçiniz yazınız.',
        'city_id.exists' => 'Lütfen geçerli bir şehir seçiniz yazınız.',
        'district_id.required' => 'Lütfen ilçe seçiniz yazınız.',
        'district_id.exists' => 'Lütfen geçerli bir ilçe seçiniz yazınız.',
        'name.required' => 'Şube adını yazınız.',
        'email.required' => 'Şubenin eposta adresini yazınız.',
        'email.email' => 'Geçerli bir eposta adresi yazınız.',
        'email.unique' => 'Bu eposta adresi başkası tarafından kullanılmaktadır.',
        'phone.required' => 'Şube telefonunu yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.tenant.branch.branch-create');
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(BranchService $branchService)
    {
        $this->validate();
        $this->branchService = $branchService;
        DB::beginTransaction();
        try {
            $branch = $this->branchService->create([
                'account_id' => $this->account_id,
                'city_id' => $this->city_id ?? null,
                'district_id' => $this->district_id ?? null,
                'name' => $this->name,
                'phone' => $this->phone??null,
                'email' => $this->email,
                'status' => $this->status == false ? 0 : 1,
            ]);

            $branch->syncRoles('branch');

            foreach($this->branch_type_categories as $k => $t)
            {
                $data = [
                    'branch_type_category_id' => $k,
                    'branch_type_id' => $t,
                    'branch_id' => $branch->id];
                $l = BranchTypeCategoryBranchTypeBranch::query();
                if(!$l->where($data)->exists()) {
                    $l->create($data);
                }
            }

            $this->dispatch('pg:eventRefresh-BranchTable');
            $msg = 'Yeni şube oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset('name');
        } catch (\Exception $exception) {
            $error = "Şube oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
