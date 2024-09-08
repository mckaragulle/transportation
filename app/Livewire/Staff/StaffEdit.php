<?php

namespace App\Livewire\Staff;

use App\Models\Staff;
use App\Models\StaffType;
use App\Models\StaffTypeCategory;
use App\Services\StaffService;
use App\Services\StaffTypeCategoryService;
use App\Services\StaffTypeService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class StaffEdit extends Component
{
    use LivewireAlert, WithFileUploads;

    public null|Collection $staffTypeCategoryDatas;
    public null|Collection $staffs;

    public ?Staff $staff = null;

    public null|array $staff_type_categories = [];
    public null|array $staff_types = [];
    public $oldfilename;
    public $filename;
    public null|int $id_number;
    public null|string $name;
    public null|string $surname;
    public null|string $phone1;
    public null|string $phone2;
    public null|string $email;
    public null|string $detail;
    
    public bool $status = true;

    protected StaffTypeCategoryService $staffTypeCategoryService;
    protected StaffTypeService $staffTypeService;
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

    public function mount($id = null, StaffTypeCategory $staffTypeCategory, StaffService $staffService)
    {
        if (!is_null($id)) {
            $this->staff = $staffService->findById($id);
            $this->status = $this->staff->status;
            $this->id_number = $this->staff->id_number;
            $this->name = $this->staff->name;
            $this->surname = $this->staff->surname;
            $this->phone1 = $this->staff->phone1;
            $this->phone2 = $this->staff->phone2;
            $this->email = $this->staff->email;
            $this->detail = $this->staff->detail;
            if (isset($this->staff?->filename) && Storage::exists($this->staff?->filename)) {
                $this->oldfilename = $this->staff->filename;
            }
            //staff_type_categories
            $this->staff_type_categories = $this->staff_types = $this->staff->staff_types->pluck('id', 'staff_type_category_id')->toArray();
            $this->staffTypeCategoryDatas = $staffTypeCategory->query()
                ->with(['staff_types:id,staff_type_category_id,staff_type_id,name', 'staff_types.staff_types:id,staff_type_category_id,staff_type_id,name'])
                ->get(['id', 'name']);
        } else {
            return $this->redirect(route('staffs.list'));
        }
    }

    public function render()
    {
        return view('livewire.staff.staff-edit');
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
            $this->staff->id_number = $this->id_number;
            $this->staff->name = $this->name;
            $this->staff->surname = $this->surname;
            $this->staff->phone1 = $this->phone1;
            $this->staff->phone2 = $this->phone2;
            $this->staff->email = $this->email;
            $this->staff->detail = $this->detail;

            $filename = null;
            if (!is_null($this->filename)) {
                $filename = $this->filename->store(path: 'public/photos');
                $this->staff->filename = $filename;
            }
            $this->staff->status = $this->status == false ? 0 : 1;
            $this->staff->save();
            if (!is_null($this->oldfilename) && Storage::exists($this->oldfilename)) {
                if (!is_null($filename) && Storage::exists($filename)) {
                    Storage::delete($this->oldfilename);
                }
            }

            foreach ($this->staff_type_categories as $staff_type_category_id => $staff_type_id) {
                DB::table('staff_type_category_staff_type_staff')
                    ->where(['staff_type_category_id' => $staff_type_category_id, 'staff_id' => $this->staff->id])
                    ->delete();
            }
            foreach ($this->staff_type_categories as $staff_type_category_id => $staff_type_id) {
                $data = DB::table('staff_type_category_staff_type_staff')
                    ->where(['staff_type_category_id' => $staff_type_category_id, 'staff_id' => $this->staff->id])
                    ->first();
                DB::insert('insert into staff_type_category_staff_type_staff (staff_type_category_id, staff_type_id, staff_id) values (?, ?, ?)', [$staff_type_category_id, $staff_type_id, $this->staff->id]);
            }

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

    public function updatedStaffTypeCategoryId()
    {
        $this->staff_types = StaffType::query()
            ->where(['staff_type_category_id' => $this->staff_type_category_id])
            ->with('staff_type')
            ->orderBy('id')
            ->get(['id', 'staff_type_id', 'name']);
    }
}
