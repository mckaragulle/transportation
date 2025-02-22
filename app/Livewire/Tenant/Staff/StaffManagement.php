<?php

namespace App\Livewire\Tenant\Staff;

use App\Services\Tenant\StaffService;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class StaffManagement extends Component
{
    public null|Model $staff = null;
    public null|string $id_number = null;
    public null|string $name = null;
    public null|string $surname = null;
    public null|string $phone1 = null;
    public null|string $phone2 = null;
    public null|string $email = null;
    public null|string $archive_number = null;
    public null|string $detail = null;
    public null|string $filename = null;
    public bool $status = true;

    public function mount($id = null, StaffService $staffService)
    {
        $this->staff = $staffService->findById($id);
        $this->id_number = $this->staff->id_number;
        $this->name = $this->staff->name;
        $this->surname = $this->staff->surname;
        $this->phone1 = $this->staff->phone1;
        $this->phone2 = $this->staff->phone2;
        $this->email = $this->staff->email;
        $this->archive_number = $this->staff->archive_number;
        $this->detail = $this->staff->detail;
        $this->filename = $this->staff->filename;
    }

    public function render()
    {
        return view('livewire.tenant.staff.staff-management');
    }
}
