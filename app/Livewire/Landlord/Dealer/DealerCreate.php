<?php

namespace App\Livewire\Landlord\Dealer;

use App\Services\Tenant\DealerService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class DealerCreate extends Component
{
    use LivewireAlert;

    public null|array $dealer_type_categories = [];
    public null|Collection $dealerTypeCategoryDatas;
    public null|string $name;
    public null|string $phone;
    public null|string $email;
    public null|string $password;
    public null|string $password_confirmation;
    public null|string $shortname = null;
    public null|string $detail = null;
    public null|string $tax = null;
    public null|string $taxoffice = null;
    public $filename;

    public bool $status = true;

    protected DealerService $dealerService;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'dealer_type_categories' => ['nullable', 'array'],
        'dealer_type_categories.*' => ['nullable'],
        'name' => ['required'],
        'phone' => ['nullable', ],
        'email' => ['required', 'email', 'unique:dealers,email'],
        'password' => ['required', 'confirmed', 'min:6'],
        'shortname' => ['required'],
        'detail' => ['nullable'],
        'tax' => ['nullable'],
        'taxoffice' => ['nullable'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
        'filename' => ['nullable', 'max:4096'],
    ];

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
        'shortname.required' => 'Bayi kısa adını yazınız.',
        'phone.required' => 'Bayi telefonunu yazınız.',
        'filename.max' => 'Dosya boyutu en fazla 4 mb olmalıdır.',
        'filename.uploaded' => 'Dosya boyutu en fazla 4 mb olmalıdır.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.landlord.dealer.dealer-create');
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(DealerService $dealerService)
    {
        $this->validate();
        $this->dealerService = $dealerService;
        DB::beginTransaction();
        try {
            $dealer = $this->dealerService->create([
                'name' => $this->name,
                'phone' => $this->phone??null,
                'email' => $this->email,
                'password' => bcrypt($this->password),
                'number' => random_int(00000000, 99999999),
                'shortname' => $this->shortname,
                'detail' => $this->detail,
                'tax' => $this->tax,
                'taxoffice' => $this->taxoffice,
                'filename' => $filename ?? null,
                'status' => $this->status == false ? 0 : 1,
            ]);

            $dealer->syncRoles('dealer');

            if (is_iterable($this->dealer_type_categories) && count($this->dealer_type_categories) > 0) {
                foreach ($this->dealer_type_categories as $k => $t) {
                    if (is_array($t)) {
                        foreach ($t as $t2) {
                            $this->attachdealerTypeCategoryId($k, $t2, $dealer->id);
                        }
                    } else {
                        $this->attachdealerTypeCategoryId($k, $t, $dealer->id);
                    }
                }
            }

            $this->dispatch('pg:eventRefresh-DealerTable');
            $msg = 'Yeni bayi oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset('name');
        } catch (\Exception $exception) {
            $error = "Bayi oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }

    private function attachdealerTypeCategoryId($dealer_type_category_id, $dealer_type_id, $dealer_id)
    {
        if ($dealer_type_id > 0) {
            DB::insert('insert into dealer_type_category_dealer_type_dealer (dealer_type_category_id, dealer_type_id, dealer_id) values (?, ?, ?)', [$dealer_type_category_id, $dealer_type_id, $dealer_id]);
        }
    }
}
