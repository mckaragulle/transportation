<?php

namespace App\Livewire\Dealer;

use App\Models\Dealer;
use App\Services\DealerService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class DealerEdit extends Component
{
    use LivewireAlert;

    public null|Dealer $dealer;
    public bool $is_show = false;

    public null|string $name;
    public null|string $phone;
    public null|string $email;
    public null|string $password = null;
    public null|string $password_confirmation = null;

    public bool $status = true;

    protected DealerService $dealerService;
    /**
     * List of add/edit form rules
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
            ],
            'email' => [
                'required',
                Rule::unique('dealers')->ignore($this->dealer),
                'email'
            ],
            'phone' => [
                'nullable',
            ],
            'password' => [
                'nullable',
                'confirmed',
                'min:6',
            ],
            'password_confirmation' => [
                'required_with:password',
            ],
            'status' => [
                'in:true,false,null,0,1,active,passive,',
                'nullable',
            ],
        ];
    }

    protected $messages = [
        'name.required' => 'Bayi adını yazınız.',
        'email.required' => 'Bayinin eposta adresini yazınız.',
        'email.email' => 'Geçerli bir eposta adresi yazınız.',
        'email.unique' => 'Bu eposta adresi başkası tarafından kullanılmaktadır.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
        'password.confirmed' => 'Lütfen şifreyi tekrar yazınız.',
        'password.min' => 'Şifre en az 6 karakter olmalıdır.',
        'password_confirmation.same' => 'Lütfen aynı şifreyi tekrar yazınız.',
    ];

    public function mount($id = null, DealerService $dealerService, bool $is_show = true)
    {
        if(!is_null($id)) {
            $this->is_show = $is_show;
            $this->dealer =$dealerService->findById($id);
            $this->name = $this->dealer->name;
            $this->email = $this->dealer->email;
            $this->phone = $this->dealer->phone;
            $this->status = $this->dealer->status;
        }
        else{
            return $this->redirect(route('dealers.list'));
        }
    }

    public function render()
    {
        return view('livewire.dealer.dealer-edit');
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

            $this->dealer->phone = $this->phone??null;
            $this->dealer->status = ($this->status == false ? 0 : 1);
            $this->dealer->save();

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
}
