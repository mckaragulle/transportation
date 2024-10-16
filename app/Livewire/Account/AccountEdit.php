<?php

namespace App\Livewire\Account;

use App\Models\Account;
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
use Illuminate\Support\Facades\Storage;

class AccountEdit extends Component
{
    use LivewireAlert, WithFileUploads;

    public null|Collection $accountTypeCategoryDatas;
    public null|Collection $accounts;

    public ?Account $account = null;

    public null|array $account_type_categories = [];
    public null|array $account_types = [];
    public null|string $number = null;
    public null|string $name = null;
    public null|string $shortname = null;
    public null|string $email = null;
    public null|string $phone = null;
    public null|string $detail = null;
    public $oldfilename;
    public $filename;
    public bool $status = true;

    protected AccountTypeCategoryService $accountTypeCategoryService;
    protected AccountTypeService $accountTypeService;
    /**
     * List of add/edit form rules
     */
    public function rules()
    {
        return [
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
    }

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

    public function mount($id = null, AccountTypeCategory $accountTypeCategory, AccountService $accountService)
    {
        if (!is_null($id)) {
            $this->account = $accountService->findById($id);
            $this->status = $this->account->status;
            $this->number = $this->account->number;
            $this->name = $this->account->name;
            $this->shortname = $this->account->shortname;
            $this->email = $this->account->email;
            $this->phone = $this->account->phone;
            $this->detail = $this->account->detail;
            $this->accountTypeCategoryDatas = $accountTypeCategory->query()
                ->with(['account_types:id,account_type_category_id,account_type_id,name', 'account_types.account_types:id,account_type_category_id,account_type_id,name'])
                ->get(['id', 'name', 'is_required', 'is_multiple']);
                
                $b = [];
                foreach($this->account->account_types as $a){
                    $b[$a->account_type_category_id][] = $a->id;
                }
                $this->account_type_categories = $b;
        } else {
            return $this->redirect(route('accounts.list'));
        }
    }

    public function render()
    {
        return view('livewire.account.account-edit');
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
            $this->account->number = $this->number;
            $this->account->name = $this->name;
            $this->account->shortname = $this->shortname;
            $this->account->email = $this->email;
            $this->account->phone = $this->phone;
            $this->account->detail = $this->detail;

            $filename = null;
            if (!is_null($this->filename)) {
                $filename = $this->filename->store(path: 'public/photos');
                $this->account->filename = $filename;
            }
            $this->account->status = $this->status == false ? 0 : 1;
            $this->account->save();
            if (!is_null($this->oldfilename) && Storage::exists($this->oldfilename)) {
                if (!is_null($filename) && Storage::exists($filename)) {
                    Storage::delete($this->oldfilename);
                }
            }

           
            foreach ($this->account_type_categories as $account_type_category_id => $account_type_id) {
                if(is_array($account_type_id)){
                    foreach($account_type_id as $t2)
                    {
                        $this->detachAccountTypeCategoryId($account_type_category_id, $this->account->id);
                    } 
                }
                else {
                    $this->detachAccountTypeCategoryId($account_type_category_id, $this->account->id);
                    
                }
            }

            foreach ($this->account_type_categories as $account_type_category_id => $account_type_id) {
                if(is_array($account_type_id)){
                    foreach($account_type_id as $t2)
                    {
                        if($t2 > 0){
                            $this->attachAccountTypeCategoryId($account_type_category_id, $t2,$this->account->id);
                        }
                    } 
                }
                else {
                    if($account_type_id > 0){
                        $this->attachAccountTypeCategoryId($account_type_category_id, $account_type_id, $this->account->id);
                    }
                }
            }

            $msg = 'Cari güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Cari güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }

    public function updatedAccountTypeCategoryId()
    {
        $this->account_types = AccountType::query()
            ->where(['account_type_category_id' => $this->account_type_category_id])
            ->with('account_type')
            ->orderBy('id')
            ->get(['id', 'account_type_id', 'name']);
    }

    private function detachAccountTypeCategoryId($account_type_category_id, $account_id){
        DB::table('account_type_category_account_type_account')
        ->where(['account_type_category_id' => $account_type_category_id, 'account_id' => $account_id])
        ->delete();
    }
    
    private function attachAccountTypeCategoryId($account_type_category_id, $account_type_id, $account_id){
        // DB::table('account_type_category_account_type_account')
        //     ->where(['account_type_category_id' => $account_type_category_id, 'account_id' => $account_id])
        //     ->first();
        DB::insert('insert into account_type_category_account_type_account (account_type_category_id, account_type_id, account_id) values (?, ?, ?)', [$account_type_category_id, $account_type_id, $account_id]);
    }
}
