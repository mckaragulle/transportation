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
    public null|string $number = null;
    public null|string $name = null;
    public null|string $shortname = null;
    public null|string $email = null;
    public null|string $phone = null;
    public null|string $detail = null;
    public $filename;

    public bool $status = true;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'account_type_categories' => ['required', 'array'],
        'account_type_categories.*' => ['required'],
        'number' => ['required'],
        'name' => ['required'],
        'shortname' => ['required'],
        'phone' => ['nullable'],
        'email' => ['nullable', 'email'],
        'detail' => ['nullable'],
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
        return view('livewire.account.account-create');
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

            if(!is_null($this->filename)){
                $filename = $this->filename->store(path: 'public/photos');
            }
            $account = $accountService->create([
                'name' => $this->name,
                'number' => $this->number,
                'shortname' => $this->shortname,
                'email' => $this->email,
                'phone' => $this->phone,
                'detail' => $this->detail,
                'filename' => $filename ?? null,
                'status' => $this->status == false ? 0 : 1,
            ]);

            foreach($this->account_type_categories as $k => $t)
            {
                if(is_array($t)){
                    foreach($t as $t2)
                    {
                        $this->attachAccountTypeCategoryId($k, $t2, $account->id);
                    } 
                }
                else {
                    $this->attachAccountTypeCategoryId($k, $t, $account->id);
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

    public function updated()
    {
        $this->validate();    
    }

    private function attachAccountTypeCategoryId($account_type_category_id, $account_type_id, $account_id)
    {
        // DB::table('account_type_category_account_type_account')
        //     ->where(['account_type_category_id' => $account_type_category_id, 'account_id' => $account_id])
        //     ->first();
        if($account_type_id > 0){
            DB::insert('insert into account_type_category_account_type_account (account_type_category_id, account_type_id, account_id) values (?, ?, ?)', [$account_type_category_id, $account_type_id, $account_id]);
        }
    }
}