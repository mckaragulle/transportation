<?php

namespace App\Livewire\Admin;

use App\Enum\EventTypeEnum;
use App\Services\AdminService;
use App\Services\RoleService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class AdminCreate extends Component
{
    use LivewireAlert;

    public null|Collection $roles;

    public null|string $role = null;
    public null|string $name;
    public null|string $email;
    public null|string $password;
    public null|string $password_confirmation;

    public bool $status = true;

    protected AdminService $adminService;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'name' => ['required'],
        'email' => ['required', 'email', 'unique:admins,email'],
        'password' => ['required', 'confirmed', 'min:6'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'name.required' => 'Yönetici adını yazınız.',
        'email.required' => 'Yöneticinin eposta adresini yazınız.',
        'email.email' => 'Geçerli bir eposta adresi yazınız.',
        'email.unique' => 'Bu eposta adresi başkası tarafından kullanılmaktadır.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
        'password.required' => 'Lütfen şifreyi yazınız.',
        'password.confirmed' => 'Lütfen şifreyi tekrar yazınız.',
        'password.min' => 'Şifre en az 6 karakter olmalıdır.',
    ];

    public function render()
    {
        return view('livewire.admin.admin-create');
    }

    public function mount(RoleService $roleService)
    {
        $this->roles = $roleService->all(['id', 'name']);
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(AdminService $adminService)
    {
        $this->validate();
        $this->adminService = $adminService;
        DB::beginTransaction();
        try {
            $admin = $this->adminService->create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => bcrypt($this->password),
                'status' => $this->status == false ? 0 : 1,
            ]);

            $admin->syncRoles('admin');

            $this->dispatch('pg:eventRefresh-AdminTable');
            $msg = 'Yeni yönetici oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset();
        } catch (\Exception $exception) {
            $error = "Yönetici oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
