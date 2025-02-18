<?php

namespace App\Livewire\Tenant\Dealer;

use App\Models\Tenant\Dealer;
use App\Models\Tenant\DealerType;
use App\Models\Tenant\DealerTypeCategory;
use App\Services\DealerTypeCategoryService;
use App\Services\DealerTypeService;
use App\Services\Tenant\DealerService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class DealerEdit extends Component
{
    use LivewireAlert;

    public null|Collection $dealerTypeCategoryDatas;
    public null|Collection $officers;
    public null|Collection $addresses;
    public null|Collection $archives;

    public null|Dealer $dealer;
    public bool $is_show = false;

    public null|array $dealer_type_categories = [];
    public null|array $dealer_types = [];

    public null|string $name = null;
    public null|string $phone = null;
    public null|string $email = null;
    public null|string $password = null;
    public null|string $password_confirmation = null;

    public null|string $number = null;
    public null|string $shortname = null;
    public null|string $detail = null;
    public null|string $tax = null;
    public null|string $taxoffice = null;
    public $oldfilename;
    public $filename;

    public bool $status = true;

    protected DealerService $dealerService;

    protected DealerTypeCategoryService $dealerTypeCategoryService;
    protected DealerTypeService $dealerTypeService;

    /**
     * List of add/edit form rules
     */
    public function rules()
    {
        return [
            'dealer_type_categories' => ['nullable', 'array'],
            'dealer_type_categories.*' => ['nullable'],
            'name' => ['required'],
            'phone' => ['nullable', ],
            'email' => ['required', 'email', Rule::unique('dealers')->ignore($this->dealer),],
            'password' => ['nullable', 'confirmed', 'min:6'],
            'number' => ['required'],
            'shortname' => ['required'],
            'detail' => ['nullable'],
            'tax' => ['nullable'],
            'taxoffice' => ['nullable'],
            'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
            'filename' => ['nullable', 'max:4096'],
        ];
    }

    protected $messages = [
        'dealer_type_categories.required' => 'Lütfen bayi kategorisini seçiniz.',
        'dealer_type_categories.array' => 'Lütfen geçerli bir bayi kategorisi seçiniz.',
        'name.required' => 'Bayi adını yazınız.',
        'email.required' => 'Bayinin eposta adresini yazınız.',
        'email.email' => 'Geçerli bir eposta adresi yazınız.',
        'email.unique' => 'Bu eposta adresi başkası tarafından kullanılmaktadır.',
        'password.required' => 'Lütfen şifreyi yazınız.',
        'password.confirmed' => 'Lütfen şifreyi tekrar yazınız.',
        'password.min' => 'Şifre en az 6 karakter olmalıdır.',
        'number.required' => 'Bayi numarasını yazınız.',
        'shortname.required' => 'Bayi kısa adını yazınız.',
        'phone.required' => 'Bayi telefonunu yazınız.',
        'filename.max' => 'Dosya boyutu en fazla 4 mb olmalıdır.',
        'filename.uploaded' => 'Dosya boyutu en fazla 4 mb olmalıdır.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null, DealerTypeCategory $dealerTypeCategory, DealerService $dealerService, bool $is_show = true)
    {
        if(!is_null($id)) {
            $this->is_show = $is_show;
            $this->dealer =$dealerService->findById($id);
            $this->name = $this->dealer->name;
            $this->email = $this->dealer->email;
            $this->phone = $this->dealer->phone;
            $this->status = $this->dealer->status;
            $this->number = $this->dealer->number;
            $this->shortname = $this->dealer->shortname;
            $this->detail = $this->dealer->detail;
            $this->tax = $this->dealer->tax;
            $this->taxoffice = $this->dealer->taxoffice;
            $this->dealerTypeCategoryDatas = $dealerTypeCategory->query()
                ->with(['dealer_types:id,dealer_type_category_id,dealer_type_id,name', 'dealer_types.dealer_types:id,dealer_type_category_id,dealer_type_id,name'])
                ->get(['id', 'name', 'is_required', 'is_multiple']);

            $b = [];
            foreach ($this->dealer->dealer_types as $a) {
                $b[$a->dealer_type_category_id][] = $a->id;
            }
            $this->dealer_type_categories = $b;
        }
        else{
            return $this->redirect(route('dealers.list'));
        }
    }

    public function render()
    {
        return view('livewire.tenant.dealer.dealer-edit');
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
            $this->dealer->name = $this->name;
            $this->dealer->email = $this->email;

            if(!is_null($this->password) && $this->password != "" && $this->password === $this->password_confirmation) {
                $this->dealer->password = bcrypt($this->password);
            }

            $this->dealer->shortname = $this->shortname;
            $this->dealer->phone = $this->phone;
            $this->dealer->detail = $this->detail;
            $this->dealer->tax = $this->tax;
            $this->dealer->taxoffice = $this->taxoffice;
            $this->dealer->status = ($this->status == false ? 0 : 1);
            $this->dealer->save();

            foreach ($this->dealer_type_categories as $dealer_type_category_id => $dealer_type_id) {
                if (is_array($dealer_type_id)) {
                    foreach ($dealer_type_id as $t2) {
                        $this->detachDealerTypeCategoryId($dealer_type_category_id, $this->dealer->id);
                    }
                } else {
                    $this->detachDealerTypeCategoryId($dealer_type_category_id, $this->dealer->id);
                }
            }

            foreach ($this->dealer_type_categories as $dealer_type_category_id => $dealer_type_id) {
                if (is_array($dealer_type_id)) {
                    foreach ($dealer_type_id as $t2) {
                        if ($t2 > 0) {
                            $this->attachDealerTypeCategoryId($dealer_type_category_id, $t2, $this->dealer->id);
                        }
                    }
                } else {
                    if ($dealer_type_id > 0) {
                        $this->attachDealerTypeCategoryId($dealer_type_category_id, $dealer_type_id, $this->dealer->id);
                    }
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
    public function updatedDealerTypeCategoryId()
    {
        $this->dealer_types = DealerType::query()
            ->where(['dealer_type_category_id' => $this->dealer_type_category_id])
            ->with('dealer_type')
            ->orderBy('id')
            ->get(['id', 'dealer_type_id', 'name']);
    }

    private function detachDealerTypeCategoryId($dealer_type_category_id, $dealer_id)
    {
        DB::table('dealer_type_category_dealer_type_dealer')
            ->where(['dealer_type_category_id' => $dealer_type_category_id, 'dealer_id' => $dealer_id])
            ->delete();
    }

    private function attachDealerTypeCategoryId($dealer_type_category_id, $dealer_type_id, $dealer_id)
    {
        DB::insert('insert into dealer_type_category_dealer_type_dealer (dealer_type_category_id, dealer_type_id, dealer_id) values (?, ?, ?)', [$dealer_type_category_id, $dealer_type_id, $dealer_id]);
    }
}
