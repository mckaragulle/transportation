<?php

namespace App\Livewire\Tenant\Staff;

use App\Models\Tenant\StaffTypeCategory;
use App\Models\Tenant\StaffTypeCategoryStaffTypeStaff;
use App\Services\Tenant\StaffService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class StaffCreate extends Component
{
    use LivewireAlert, WithFileUploads;

    public null|array $staff_type_categories = [];
    public null|Collection $staffTypeCategoryDatas;
    public null|Collection $staffs;
    public null|int $id_number = null;
    public null|string $name = null;
    public null|string $surname = null;
    public null|string $phone1 = null;
    public null|string $phone2 = null;
    public null|string $email = null;
    public null|string $detail = null;
    public $filename;


    public bool $status = true;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'staff_type_categories' => ['required', 'array'],
        'staff_type_categories.*' => ['required'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
        'id_number' => ['required'],
        'name' => ['required'],
        'surname' => ['required'],
        'phone1' => ['required'],
        'phone2' => ['nullable'],
        'email' => ['nullable', 'email'],
        'detail' => ['nullable'],
        'filename' => ['nullable', 'image', 'max:4096'],
    ];

    protected $messages = [
        'staff_type_categories.required' => 'Lütfen staff kategorisini seçiniz.',
        'staff_type_categories.array' => 'Lütfen geçerli bir staff kategorisi seçiniz.',
        'id_number.required' => 'Personel TC kimlik numarasını yazınız.',
        'name.required' => 'Personel adını yazınız.',
        'surname.required' => 'Personel soyadını yazınız.',
        'phone1.required' => 'Personel telefon numarasını yazınız.',
        'email.email' => 'Personel için geçerli bir eposta adresi yazınız.',
        'filename.image' => 'Personel için dosya seçiniz yazınız.',
        'filename.max' => 'Dosya boyutu en fazla 4 mb olmalıdır.',
        'filename.uploaded' => 'Dosya boyutu en fazla 1 mb olmalıdır.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.tenant.staff.staff-create');
    }

    public function mount(StaffTypeCategory $staffTypeCategory)
    {
        $this->staffTypeCategoryDatas = $staffTypeCategory->query()
            ->whereIn('target', ['all', 'staff'])
            ->with(['staff_types:id,staff_type_category_id,staff_type_id,name', 'staff_types.staff_types:id,staff_type_category_id,staff_type_id,name'])
            ->get(['id', 'name']);
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(StaffService $staffService)
    {
        $this->validate();
        DB::beginTransaction();
        try {

            if ($this->filename != null) {
                $filename = $this->filename->store(path: 'public/photos');
            }
            $staff = $staffService->create([
                'id_number' => $this->id_number,
                'name' => $this->name,
                'surname' => $this->surname,
                'phone1' => $this->phone1,
                'phone2' => $this->phone2,
                'email' => $this->email,
                'detail' => $this->detail,
                'filename' => $filename ?? null,
                'status' => $this->status == false ? 0 : 1,
            ]);

            foreach ($this->staff_type_categories as $k => $t) {
                $data = [
                    'staff_type_category_id' => $k,
                    'staff_type_id' => $t,
                    'staff_id' => $staff->id];
                $l = StaffTypeCategoryStaffTypeStaff::query();
                if(!$l->where($data)->exists()) {
                    $l->create($data);
                }
            }


            $this->dispatch('pg:eventRefresh-StaffTable');
            $msg = 'Personel oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset([
                'id_number',
                'name',
                'surname',
                'phone1',
                'phone2',
                'email',
                'detail'
            ]);
        } catch (\Exception $exception) {
            $error = "Personel oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }

    public function updated()
    {
        $this->validate();
    }
}
