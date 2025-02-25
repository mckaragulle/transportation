<?php

namespace App\Livewire\Tenant\Branch;

use App\Models\Tenant\BranchTypeCategory;
use App\Models\Tenant\BranchTypeCategoryBranchTypeBranch;
use App\Services\Tenant\BranchTypeCategoryService;
use App\Services\Tenant\BranchTypeService;
use App\Services\Tenant\BranchService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class BranchEdit extends Component
{
    use LivewireAlert;

    public null|Collection $branchTypeCategoryDatas;
    public null|Collection $accounts = null;
    public null|Collection $cities = null;
    public null|Collection $districts = null;

    public null|Model $branch;
    public bool $is_show = false;

    public null|array $branch_type_categories = [];
    public null|array $branch_types = [];

    public null|string $account_id = null;
    public null|string $city_id = null;
    public null|string $district_id = null;
    public null|string $name;
    public null|string $phone;
    public null|string $email;

    public bool $status = true;

    protected BranchService $branchService;

    // protected BranchTypeCategoryService $branchTypeCategoryService;
    // protected BranchTypeService $branchTypeService;

    /**
     * List of add/edit form rules
     */
    public function rules()
    {
        return [
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
    }

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

    public function mount($id = null, BranchTypeCategory $branchTypeCategory, BranchService $branchService, bool $is_show = true)
    {
        if(!is_null($id)) {
            $this->is_show = $is_show;
            $this->branch =$branchService->findById($id);
            $this->account_id = $this->branch->account_id;
            $this->city_id = $this->branch->city_id;
            $this->district_id = $this->branch->district_id;
            $this->name = $this->branch->name;
            $this->email = $this->branch->email;
            $this->phone = $this->branch->phone;
            $this->status = $this->branch->status;
            $this->branchTypeCategoryDatas = $branchTypeCategory->query()
                ->with(['branch_types:id,branch_type_category_id,branch_type_id,name', 'branch_types.branch_types:id,branch_type_category_id,branch_type_id,name'])
                ->get(['id', 'name', 'is_required', 'is_multiple']);

            $b = [];
            foreach ($this->branch->branch_types as $a) {
                $b[$a->branch_type_category_id][] = $a->id;
            }
            $this->branch_type_categories = $b;
        }
        else{
            return $this->redirect(route('branchs.list'));
        }
    }

    public function render()
    {
        return view('livewire.tenant.branch.branch-edit');
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
            $this->branch->name = $this->name;
            $this->branch->email = $this->email;

            if(!is_null($this->password) && $this->password != "" && $this->password === $this->password_confirmation) {
                $this->branch->password = bcrypt($this->password);
            }

            $this->branch->shortname = $this->shortname;
            $this->branch->phone = $this->phone;
            $this->branch->detail = $this->detail;
            $this->branch->tax = $this->tax;
            $this->branch->taxoffice = $this->taxoffice;
            $this->branch->status = ($this->status == false ? 0 : 1);
            $this->branch->save();

            foreach ($this->branch_type_categories as $branch_type_category_id => $branch_type_id) {
                $where = ['branch_type_category_id' => $branch_type_category_id, 'branch_id' => $this->branch->id];
                BranchTypeCategoryBranchTypeBranch::query()->where($where)->delete();
            }
            foreach ($this->branch_type_categories as $branch_type_category_id => $branch_type_id) {
                $data = [
                    'branch_type_category_id' => $branch_type_category_id,
                    'branch_type_id' => $branch_type_id,
                    'branch_id' => $this->branch->id];
                $l = BranchTypeCategoryBranchTypeBranch::query();
                if(!$l->where($data)->exists()) {
                    $l->create($data);
                }
            }

            $msg = 'Bayi güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Bayi güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
    public function updatedBranchTypeCategoryId()
    {
        $this->branch_types = BranchType::query()
            ->where(['branch_type_category_id' => $this->branch_type_category_id])
            ->with('branch_type')
            ->orderBy('id')
            ->get(['id', 'branch_type_id', 'name']);
    }

    private function detachBranchTypeCategoryId($branch_type_category_id, $branch_id)
    {
        DB::table('branch_type_category_branch_type_branch')
            ->where(['branch_type_category_id' => $branch_type_category_id, 'branch_id' => $branch_id])
            ->delete();
    }

    private function attachBranchTypeCategoryId($branch_type_category_id, $branch_type_id, $branch_id)
    {
        DB::insert('insert into branch_type_category_branch_type_branch (branch_type_category_id, branch_type_id, branch_id) values (?, ?, ?)', [$branch_type_category_id, $branch_type_id, $branch_id]);
    }
}
