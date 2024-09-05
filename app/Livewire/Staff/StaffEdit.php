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
    public null|int $number;
    public $oldfilename;
    public $filename;
    public null|string $buyed_at;
    public null|string $canceled_at;
    public bool $status = true;

    protected StaffTypeCategoryService $staffTypeCategoryService;
    protected StaffTypeService $staffTypeService;
    /**
     * List of add/edit form rules
     */
    public function rules()
    {
        return [
            'staff_type_categories' => ['required', 'array'],
            'staff_type_categories.*' => ['required'],
            'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
            'number' => ['required'],
            'filename' => ['nullable', 'image', 'max:4096'],
            'buyed_at' => ['required', 'date'],
            'canceled_at' => ['nullable', 'date'],
        ];
    }

    protected $messages = [
        'staff_type_category_id.required' => 'Lütfen staff kategorisini seçiniz.',
        'staff_type_category_id.exists' => 'Lütfen geçerli bir staff kategorisi seçiniz.',
        'staff_type_id.required' => 'Lütfen staff tipi seçiniz.',
        'staff_type_id.exists' => 'Lütfen geçerli bir staff tipi seçiniz.',
        'number.required' => 'Staff numarasını yazınız.',
        'filename.image' => 'Staff için dosya seçiniz yazınız.',
        'filename.max' => 'Dosya boyutu en fazla 1 mb olmalıdır.',
        'filename.uploaded' => 'Dosya boyutu en fazla 1 mb olmalıdır.',
        'buyed_at.required' => 'Staff için satın alma tarihi seçiniz yazınız.',
        'buyed_at.date' => 'Staff için geçerli bir satın alma tarihi seçiniz yazınız.',
        'canceled_at.date' => 'Staff için geçerli bir iptal edilme tarihi seçiniz yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null, StaffTypeCategory $staffTypeCategory, StaffService $staffService)
    {
        if (!is_null($id)) {
            $this->staff = $staffService->findById($id);
            $this->status = $this->staff->status;
            $this->number = $this->staff->number;
            if (isset($this->staff?->filename) && Storage::exists($this->staff?->filename)) {
                $this->oldfilename = $this->staff->filename;
            }
            $this->buyed_at = $this->staff->buyed_at ?? null;
            $this->canceled_at = $this->staff->canceled_at ?? null;
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
            $this->staff->number = $this->number;

            $filename = null;
            if (!is_null($this->filename)) {
                $filename = $this->filename->store(path: 'public/photos');
                $this->staff->filename = $filename;
            }

            $this->staff->buyed_at = $this->buyed_at ?? null;
            $this->staff->canceled_at = $this->canceled_at ?? null;
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

            $msg = 'Staff güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Staff güncellenemedi. {$exception->getMessage()}";
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
