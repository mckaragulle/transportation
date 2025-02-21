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

class StaffTypeEdit extends Component
{
    use LivewireAlert;

    public null|Collection $staffTypeCategories;
    public null|Collection $staffTypes;

    public ?LandlordStaffType $staffType = null;

    public null|int $staff_type_category_id = null;
    public null|int $staff_type_id = null;
    public null|string $name;
    public bool $status = true;

    protected LandlordStaffTypeCategoryService $staffTypeCategoryService;
    protected LandlordStaffTypeService $staffTypeService;
    /**
     * List of add/edit form rules
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
            ],
            'staff_type_category_id' => [
                'required',
                'exists:staff_type_categories,id',
            ],
            'staff_type_id' => [
                'nullable',
                'exists:staff_types,id',
            ],
            'status' => [
                'in:true,false,null,0,1,active,passive,',
                'nullable',
            ],
        ];
    }

    protected $messages = [
        'staff_type_category_id.required' => 'Lütfen personel kategorisini seçiniz.',
        'staff_type_category_id.exists' => 'Lütfen geçerli bir personel kategorisi seçiniz.',
        'staff_type_id.exists' => 'Lütfen geçerli bir personel seçeneği seçiniz.',
        'name.required' => 'Personel seçeneği adını yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null, LandlordStaffTypeCategoryService $staffTypeCategoryService, LandlordStaffTypeService $staffTypeService)
    {
        if (!is_null($id)) {
            $this->staffType = $staffTypeService->findById($id);
            $this->staff_type_category_id = $this->staffType->staff_type_category_id;
            $this->staff_type_id = $this->staffType->staff_type_id??null;
            $this->name = $this->staffType->name??null;
            $this->status = $this->staffType->status;
            $this->staffTypeCategories = $staffTypeCategoryService->all();
            $this->staffTypes = LandlordStaffType::query()
                ->where(['staff_type_category_id' => $this->staff_type_category_id])
                ->with('staff_type')
                ->orderBy('id')
                ->get();

        } else {
            return $this->redirect(route('staffTypes.list'));
        }
    }

    public function render()
    {
        return view('livewire.landlord.staff-type.staff-type-edit');
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
            $this->staffType->staff_type_category_id = $this->staff_type_category_id;
            $this->staffType->staff_type_id = $this->staff_type_id ?? null;
            $this->staffType->name = $this->name;
            $this->staffType->status = $this->status == false ? 0 : 1;
            $this->staffType->save();

            $msg = 'Personel seçeneği güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Personel seçeneği güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }

    public function updatedStaffTypeCategoryId()
    {
        $this->staffTypes = LandlordStaffType::query()->where(['staff_type_category_id' => $this->staff_type_category_id])->with('staff_type')->orderBy('id')->get(['id', 'staff_type_id', 'name']);
    }
}
