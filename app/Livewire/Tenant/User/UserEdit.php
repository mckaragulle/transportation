<?php

namespace App\Livewire\Tenant\User;

use App\Models\Tenant\Dealer;
use App\Models\Tenant\User;
use App\Services\Tenant\DealerService;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserEdit extends Component
{
    use LivewireAlert;

    public null|Collection $dealers;
    public null|array|Collection $roles = [];

    public string $role_id;

    public null|User $user;
    public bool $is_admin = false;
    public null|string|int $dealer_id;
    public null|string|int $role;
    public null|string $name;
    public null|string $email;
    public null|string $phone;
    public null|string $password = null;
    public null|string $password_confirmation = null;

    public bool $status;

    protected UserService $userService;
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
                Rule::unique('users')->ignore($this->user),
                'email'
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
        'name.required' => 'Personel adını yazınız.',
        'email.required' => 'Personelin eposta adresini yazınız.',
        'email.email' => 'Geçerli bir eposta adresi yazınız.',
        'email.unique' => 'Bu eposta adresi başkası tarafından kullanılmaktadır.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
        'password.confirmed' => 'Lütfen şifreyi tekrar yazınız.',
        'password.min' => 'Şifre en az 6 karakter olmalıdır.',
        'password_confirmation.same' => 'Lütfen aynı şifreyi tekrar yazınız.',
    ];



    public function render()
    {
        return view('livewire.tenant.user.user-edit');
    }

    public function mount($id = null, Dealer $dealer, Role $role, UserService $userService, DealerService $dealerService)
    {
        if(!is_null($id)) {

            $this->is_admin = Auth::user()->hasRole('admin');

            if($this->is_admin){
                $this->user = $userService->findById($id);
                $this->dealers = $dealerService->all(['id', 'name']);
            }
            else if (Auth::user()->hasRole('bayi')) {
                $this->dealer_id = auth()->user()->id;
                $user = User::query()->where(['id' => $id, 'dealer_id' => $this->dealer_id]);
                if (!$user->exists()) {
                    return redirect()->route('users.list');
                }
                $this->user = $user->first();
            }

            $this->roles = $role->query()->whereNotIn('name', ['admin', 'bayi'])->get(['id', 'name']);

            $this->role = $this->user->roles->first()->name;

            $this->status = ($this->user->status == false ? 0 : 1);
            $this->dealer_id = $this->user->dealer_id;
            $this->name = $this->user->name;
            $this->email = $this->user->email;
            $this->phone = $this->user->phone;
        }
        else{
            return $this->redirect(route('users.list'));
        }
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

            if(!is_null($this->password) && $this->password != "" && $this->password === $this->password_confirmation) {
                $this->user->password = bcrypt($this->password);
            }

            $this->user->dealer_id = $this->dealer_id;
            $this->user->name = $this->name;
            $this->user->email = $this->email;
            $this->user->phone = $this->phone;
            $this->user->status = ($this->status == false ? 0 : 1);
            $this->user->save();

            $this->user->syncRoles($this->role);

            $msg = 'Personel güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Personel güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
