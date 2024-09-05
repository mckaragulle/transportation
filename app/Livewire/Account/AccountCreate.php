<?php

namespace App\Livewire\Account;

use App\Models\AccountType;
use App\Models\AccountTypeCategory;
use App\Services\AccountService;
use App\Services\AccountTypeCategoryService;
use App\Services\AccountTypeService;
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
    public null|string $name = null;
    public null|string $email = null;
    public null|string $phone = null;
    public null|string $address = null;
    public null|string $detail = null;
    public $filename;

    public bool $status = true;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'account_type_categories' => ['required', 'array'],
        'account_type_categories.*' => ['required'],
        'name' => ['required'],
        'phone' => ['nullable'],
        'email' => ['nullable', 'email'],
        'address' => ['nullable'],
        'detail' => ['nullable'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
        'filename' => ['nullable', 'image', 'max:4096'],
    ];

    protected $messages = [
        'account_type_categories.required' => 'Lütfen cari kategorisini seçiniz.',
        'account_type_categories.array' => 'Lütfen geçerli bir cari kategorisi seçiniz.',
        'name.required' => 'Müşteri adını yazınız.',
        'phone.required' => 'Müşteri telefonunu yazınız.',
        'email.required' => 'Müşteri eposta adresini yazınız.',
        'email.email' => 'Lütfen geçerli bir eposta adresi yazınız.',
        'address.required' => 'Müşteri adresini yazınız.',
        'filename.image' => 'Müşteri için dosya seçiniz yazınız.',
        'filename.max' => 'Dosya boyutu en fazla 4 mb olmalıdır.',
        'filename.uploaded' => 'Dosya boyutu en fazla 4 mb olmalıdır.',
        
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.account.account-create');
    }

    public function mount(AccountTypeCategory $accountTypeCategory)
    {
        $this->accountTypeCategoryDatas = $accountTypeCategory->query()
        ->with(['account_types:id,account_type_category_id,account_type_id,name', 'account_types.account_types:id,account_type_category_id,account_type_id,name'])
        ->get(['id', 'name']);
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

            if(!is_null($this->filename)){
                $filename = $this->filename->store(path: 'public/photos');
            }
            $account = $accountService->create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'detail' => $this->detail,
                'filename' => $filename ?? null,
                'status' => $this->status == false ? 0 : 1,
            ]);

            foreach($this->account_type_categories as $k => $t)
            {
                DB::insert('insert into account_type_category_account_type_account (account_type_category_id, account_type_id, account_id) values (?, ?, ?)', [$k, $t, $account->id]);
            }

            $this->dispatch('pg:eventRefresh-AccountTable');
            $msg = 'Account oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset();
        } catch (\Exception $exception) {
            $error = "Account oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }

    public function updated()
    {
        $this->validate();    
    }
}