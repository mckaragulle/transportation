<?php

namespace App\Livewire\Tenant\StaffCompetence;

use App\Models\Tenant\StaffCompetence;
use App\Models\Tenant\StaffType;
use App\Models\Tenant\StaffTypeCategory;
use App\Services\Tenant\StaffCompetenceService;
use App\Services\Tenant\StaffService;
use App\Services\Tenant\StaffTypeCategoryService;
use App\Services\Tenant\StaffTypeService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;
use Livewire\Component;

class StaffCompetenceCreate extends Component
{
    use LivewireAlert;

    public null|array $staff_type_categories = [];
    public null|array $checkStaffCompetences = [];

    public null|Collection $staffTypeCategories = null;
    public null|Collection $staffTypes = null;
    public null|Model $staff = null;
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
        'staff_type_category_id' => ['required', 'exists:tenant.staff_type_categories,id'],
        'staff_type_id' => ['required', 'exists:tenant.staff_types,id'],
        'staff_id' => ['required', 'exists:tenant.staffs,id'],
        'expiry_date_at' => ['nullable', 'date'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'staff_type_category_id.required' => 'Lütfen bir yetkinlik kategorisi seçiniz.',
        'staff_type_category_id.exists' => 'Lütfen geçerli bir yetkinlik kategorisi seçiniz.',
        'staff_type_id.required' => 'Lütfen yetkinlik seçeneği seçiniz.',
        'staff_type_id.exists' => 'Lütfen geçerli bir yetkinlik seçeneği seçiniz.',
        'staff_id.required' => 'Lütfen bir personel seçiniz.',
        'staff_id.exists' => 'Lütfen geçerli bir personel seçiniz.',
        'expiry_date_at.date' => 'Lütfen geçerli bir geçerlilik tarihi seçiniz.',
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
        $this->checkStaffCompetences();

        $this->staffTypeCategories = StaffTypeCategory::query()
            ->whereIn('target', ['all', 'competence'])
            ->get(['id', 'name']);
        $this->staff_id = $this->staff->id;
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(StaffCompetenceService $staffCompetenceService)
    {
        $this->validate();
        DB::beginTransaction();
        try {
            if(!in_array($this->staff_type_category_id,$this->checkStaffCompetences)){
                $staffCompetenceService->create([
                    'staff_type_category_id' => $this->staff_type_category_id,
                    'staff_type_id' => $this->staff_type_id,
                    'staff_id' => $this->staff_id,
                    'expiry_date_at' => $this->expiry_date_at ?? null,
                    'status' => $this->status == false ? 0 : 1,
                ]);
    
                $this->dispatch('pg:eventRefresh-StaffCompetenceTable');
                $msg = 'Personel yetkinliği oluşturuldu.';
                session()->flash('message', $msg);
                $this->alert('success', $msg, ['position' => 'center']);
                DB::commit();
                
            }else{
                $this->alert('error', "Bu yetkinlik türünü zaten eklemişsiniz.", ['position' => 'center']);
            }
            $this->reset([
                'staff_type_category_id',
                'staff_type_id',
                'expiry_date_at'
            ]);
            $this->checkStaffCompetences();
            
        } catch (\Exception $exception) {
            $error = "Personel yetkinliği oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
    public function updatedStaffTypeCategoryId($value, $key)
    {
        if ($value == "") {
            $this->staffTypes = null;
        } else {
            $this->staffTypes = StaffType::query()->where('staff_type_category_id', $value)->get(['id', 'name']);
        }
    }

    #[On('refresh-checkStaffCompetences')]
    public function checkStaffCompetences(){
        $this->checkStaffCompetences = StaffCompetence::query()
            ->where('staff_id', $this->staff->id)
            ->get('staff_type_category_id')
            ->pluck('staff_type_category_id')
            ->toArray();
    }
}
