<?php

namespace App\Livewire\Admin;

use App\Models\Admin;
use App\Services\AdminService;
use App\Services\RoleService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class AdminEdit extends Component
{
    use LivewireAlert;


    public null|Collection $roles;
    public null|Admin $admin;

    public null|string $role;
    public null|string $name;
    public null|string $email;
    public null|string $password = null;
    public null|string $password_confirmation = null;

    public bool $status = true;

    protected AdminService $adminService;
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
                Rule::unique('admins')->ignore($this->admin),
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
        'name.required' => 'Yönetici adını yazınız.',
        'email.required' => 'Yöneticinin eposta adresini yazınız.',
        'email.email' => 'Geçerli bir eposta adresi yazınız.',
        'email.unique' => 'Bu eposta adresi başkası tarafından kullanılmaktadır.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
        'password.confirmed' => 'Lütfen şifreyi tekrar yazınız.',
        'password.min' => 'Şifre en az 6 karakter olmalıdır.',
        'password_confirmation.same' => 'Lütfen aynı şifreyi tekrar yazınız.',
    ];

    public function mount($id = null, Admin $admin, RoleService $roleService)
    {
        if(!is_null($id)) {
            $this->roles = $roleService->all(['id', 'name']);

            $this->admin = $admin->whereId($id)->first();
            $this->role = $this->admin->roles()->first()->name ?? null;
            $this->name = $this->admin->name;
            $this->email = $this->admin->email;
            $this->password = null;
        }
        else{
            return $this->redirect(route('admins.list'));
        }
    }

    public function render()
    {
        return view('livewire.admin.admin-edit');
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
            $this->admin->name = $this->name;
            $this->admin->email = $this->email;

            if(!is_null($this->password) && $this->password != "" && $this->password === $this->password_confirmation) {
                $this->admin->password = bcrypt($this->password);
            }

            $this->admin->status = ($this->status == false ? 0 : 1);
            $this->admin->save();

//            $this->admin->syncRoles([$this->role]);

            $msg = 'Yönetici güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Yönetici güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
