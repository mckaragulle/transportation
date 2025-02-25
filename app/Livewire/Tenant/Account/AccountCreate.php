<?php

namespace App\Livewire\Tenant\Account;

use App\Models\Tenant\AccountTypeCategory;
use App\Models\Tenant\AccountTypeCategoryAccountTypeAccount;
use App\Services\Tenant\AccountService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class AccountCreate extends Component
{
    use LivewireAlert, WithFileUploads;

    public null|array $account_type_categories = [];
    public null|Collection $accountTypeCategoryDatas;
    public null|Collection $accounts;
    public null|string $dealer_id = null;
    public null|string $number = null;
    public null|string $name = null;
    public null|string $shortname = null;
    public null|string $email = null;
    public null|string $phone = null;
    public null|string $detail = null;
    public null|string $tax = null;
    public null|string $taxoffice = null;
    public $filename;

    public bool $status = true;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'account_type_categories' => ['nullable', 'array'],
        'account_type_categories.*' => ['nullable'],
        'number' => ['required'],
        'name' => ['required'],
        'shortname' => ['required'],
        'phone' => ['nullable'],
        'email' => ['nullable', 'email'],
        'detail' => ['nullable'],
        'tax' => ['nullable'],
        'taxoffice' => ['nullable'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
        'filename' => ['nullable', 'max:4096'],
    ];

    protected $messages = [
        'account_type_categories.required' => 'Lütfen cari kategorisini seçiniz.',
        'account_type_categories.array' => 'Lütfen geçerli bir cari kategorisi seçiniz.',
        'number.required' => 'Müşteri cari numarasını yazınız.',
        'name.required' => 'Müşteri adını yazınız.',
        'shortname.required' => 'Müşteri kısa adını yazınız.',
        'phone.required' => 'Müşteri telefonunu yazınız.',
        'email.required' => 'Müşteri eposta adresini yazınız.',
        'email.email' => 'Lütfen geçerli bir eposta adresi yazınız.',
        'filename.max' => 'Dosya boyutu en fazla 4 mb olmalıdır.',
        'filename.uploaded' => 'Dosya boyutu en fazla 4 mb olmalıdır.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.tenant.account.account-create');
    }

    public function mount(AccountTypeCategory $accountTypeCategory)
    {
        $this->accountTypeCategoryDatas = $accountTypeCategory->query()
            ->with(['account_types:id,account_type_category_id,account_type_id,name', 'account_types.account_types:id,account_type_category_id,account_type_id,name'])
            ->get(['id', 'name', 'is_required', 'is_multiple']);
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(AccountService $accountService)
    {
        $this->validate();
        DB::beginTransaction();
        try {

            if ($this->filename != null) {
                $filename = $this->filename->store(path: 'public/photos');
            }
            $account = $accountService->create([
                'name' => $this->name,
                'number' => $this->number,
                'shortname' => $this->shortname,
                'email' => $this->email,
                'phone' => $this->phone,
                'detail' => $this->detail,
                'tax' => $this->tax,
                'taxoffice' => $this->taxoffice,
                'filename' => $filename ?? null,
                'status' => $this->status == false ? 0 : 1,
            ]);

            foreach($this->account_type_categories as $k => $t)
            {
                $data = [
                    'account_type_category_id' => $k,
                    'account_type_id' => $t,
                    'account_id' => $account->id];
                $l = AccountTypeCategoryAccountTypeAccount::query();
                if(!$l->where($data)->exists()) {
                    $l->create($data);
                }
            }

            $this->dispatch('pg:eventRefresh-AccountTable');
            $msg = 'Cari oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset();
        } catch (\Exception $exception) {
            $error = "Cari oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
