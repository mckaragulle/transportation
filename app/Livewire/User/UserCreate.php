<?php

namespace App\Livewire\User;

use App\Models\Dealer;
use App\Services\DealerService;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserCreate extends Component
{
    use LivewireAlert;

    public null|Collection $dealers;
    public null|array|Collection $roles = [];

    public null|string|int $dealer_id;
    public bool $is_admin = false;
    public null|string $role;

    public null|string $name;
    public null|string $email;
    public null|string $phone;
    public null|string $password;
    public null|string $password_confirmation;

    public bool $status = true;

    protected DealerService $dealerService;
    protected UserService $userService;


    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'dealer_id' => ['required', 'exists:dealers,id'],
        'name' => ['required'],
        'email' => ['required', 'email', 'unique:users,email'],
        'password' => ['required', 'confirmed', 'min:6'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'dealer_id.required' => 'Lütfen bayi seçiniz.',
        'dealer_id.exists' => 'Lütfen geçerli bir bayi seçiniz.',
        'name.required' => 'Personel adını yazınız.',
        'email.required' => 'Personelnin eposta adresini yazınız.',
        'email.email' => 'Geçerli bir eposta adresi yazınız.',
        'email.unique' => 'Bu eposta adresi başkası tarafından kullanılmaktadır.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
        'password.required' => 'Lütfen şifreyi yazınız.',
        'password.confirmed' => 'Lütfen şifreyi tekrar yazınız.',
        'password.min' => 'Şifre en az 6 karakter olmalıdır.',
    ];

    public function render()
    {
        return view('livewire.user.user-create');
    }

    public function mount(Dealer $dealer, Role $role)
    {
        $this->is_admin = Auth::user()->hasRole('admin');
        if($this->is_admin){
            $this->dealers = $dealer->query()->get();
        }
        else if(Auth::user()->hasRole('bayi')){
            $this->dealer_id = auth()->user()->id;
        }
        else {
            $this->dealer_id = auth()->user()->dealer_id??null;
        }
        $this->roles = $role->query()->whereNotIn('name', ['admin', 'bayi'])->get(['id', 'name']);
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(UserService $userService)
    {
        $this->validate();
        $this->userService = $userService;
        DB::beginTransaction();
        try {
            $user = $this->userService->create([
                'dealer_id' => $this->dealer_id,
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'password' => bcrypt($this->password),
                'status' => $this->status == false ? 0 : 1,
            ]);

            $user->syncRoles([$this->role]);

            $this->dispatch('pg:eventRefresh-UserTable');
            $msg = 'Yeni personel oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset(['name', 'email', 'phone', 'password', 'role']);
        } catch (\Exception $exception) {
            $error = "Personel oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
