<?php

namespace App\Livewire\Tenant\StaffCompetence;

use App\Models\Tenant\Staff;
use App\Services\Tenant\StaffCompetenceService;
use App\Services\Tenant\StaffService;
use App\Services\Tenant\StaffTypeCategoryService;
use App\Services\Tenant\StaffTypeService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class StaffCompetenceCreate extends Component
{
    use LivewireAlert;

    public null|Collection $staffTypeCategories = null;
    public null|Collection $StaffTypes = null;
    public null|Staff $staff = null;
    public null|string $staff_type_category_id = null;
    public null|string $staff_type_id = null;
    public null|string $staff_id = null;
    public null|string $expiry_date_at = null;

    public bool $status = true;
    public bool $is_show = false;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'staff_type_category_id' => ['required', 'exists:staff_type_categories,id'],
        'staff_type_id' => ['required', 'exists:staff_types,id'],
        'staff_id' => ['required', 'exists:staffs,id'],
        'expiry_date_at' => ['nullable', 'datetime'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'staff_type_category_id.required' => 'Lütfen bir yetkinlik kategorisi seçiniz.',
        'staff_type_category_id.exists' => 'Lütfen geçerli bir yetkinlik kategorisi seçiniz.',
        'staff_type_id.required' => 'Lütfen yetkinlik seçeneği seçiniz.',
        'staff_type_id.exists' => 'Lütfen geçerli bir yetkinlik seçeneği seçiniz.',
        'staff_id.required' => 'Lütfen bir personel seçiniz.',
        'staff_id.exists' => 'Lütfen geçerli bir personel seçiniz.',
        'expiry_date_at.datetime' => 'Lütfen geçerli bir geçerlilik tarihi seçiniz.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.tenant.staff-competence.staff-competence-create');
    }

    public function mount(null|string $id = null, bool $is_show, StaffTypeCategoryService $staffTypeCategoryService, StaffTypeService $staffTypeService, StaffService $staffService)
    {
        $this->is_show = $is_show;
        $this->staff = $staffService->findById($id);
        $this->staffTypeCategories = $staffTypeCategoryService->all(['id', 'name']);
        $this->StaffTypes = $staffTypeService->all(['id', 'name']);
        $this->staff_id = $this->staff->id;
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(StaffCompetenceService $staffTypeCategoryService)
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $staffTypeCategoryService->create([
                'staff_type_category_id' => $this->staff_type_category_id,
                'staff_type_id' => $this->staff_type_id,
                'staff_id' => $this->staff_id,
                'expiry_date_at' => $this->expiry_date_at ?? null,
                'status' => $this->status == false ? 0 : 1,
            ]);


            $this->dispatch('pg:eventRefresh-AccountBankTable');
            $msg = 'Personel yetkinlik oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset();
        } catch (\Exception $exception) {
            $error = "Personel yetkinlik bilgisi oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
