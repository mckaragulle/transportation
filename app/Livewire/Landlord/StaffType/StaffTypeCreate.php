<?php

namespace App\Livewire\Landlord\StaffType;

use App\Models\Landlord\LandlordStaffType;
use App\Services\Landlord\LandlordStaffTypeCategoryService;
use App\Services\Landlord\LandlordStaffTypeService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class StaffTypeCreate extends Component
{
    use LivewireAlert;

    public null|Collection $staffTypeCategories;
    public null|Collection $staffTypes;
    public null|string|int $staff_type_category_id = null;
    public null|string|int $staff_type_id = null;
    public null|string $name;

    public bool $status = true;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'staff_type_category_id' => ['required', 'exists:staff_type_categories,id'],
        'staff_type_id' => ['nullable', 'exists:staff_types,id'],
        'name' => ['required'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'staff_type_category_id.required' => 'Lütfen personel kategorisini seçiniz.',
        'staff_type_category_id.exists' => 'Lütfen geçerli bir personel kategorisi seçiniz.',
        'staff_type_id.exists' => 'Lütfen geçerli bir personel seçeneği seçiniz.',
        'name.required' => 'Personel seçeneği adını yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.landlord.staff-type.staff-type-create');
    }

    public function mount(LandlordStaffTypeCategoryService $staffTypeCategoryService)
    {
        $this->staffTypeCategories = $staffTypeCategoryService->all(['id', 'name']);
        $this->staffTypes = LandlordStaffType::query()
            ->where(['staff_type_category_id' => $this->staff_type_category_id])
            ->whereDoesntHave('staff_types')
            ->with('staff_type')
            ->orderBy('id')
            ->get();
        /*['id', 'staff_type_id', 'name']*/
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(LandlordStaffTypeService $staffTypeService)
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $staffType = $staffTypeService->create([
                'staff_type_category_id' => $this->staff_type_category_id ?? null,
                'staff_type_id' => $this->staff_type_id ?? null,
                'name' => $this->name,
                'status' => $this->status == false ? 0 : 1,
            ]);

            $this->dispatch('pg:eventRefresh-StaffTypeTable');
            $msg = 'Personel seçeneği oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            $this->staffTypes = LandlordStaffType::query()
                ->where(['staff_type_category_id' => $this->staff_type_category_id])
                ->whereDoesntHave('staff_types')
                ->with('staff_type')
                ->orderBy('id')
                ->get(['id', 'staff_type_id', 'name']);
            DB::commit();
            $this->reset(['name', 'staff_type_id']);

        } catch (\Exception $exception) {
            $error = "Personel seçeneği oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }

    public function updatedStaffTypeCategoryId()
    {
        $this->staffTypes = LandlordStaffType::query()->where(['staff_type_category_id' => $this->staff_type_category_id])->with('staff_type')->orderBy('id')->get();
    }
}
