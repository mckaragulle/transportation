<?php

namespace App\Livewire\Staff;

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

class StaffCreate extends Component
{
    use LivewireAlert, WithFileUploads;

    public null|array $staff_type_categories = [];
    public null|Collection $staffTypeCategoryDatas;
    public null|Collection $staffs;
    public null|int $number;
    public $filename;
    public null|string $buyed_at;
    public null|string $canceled_at;


    public bool $status = true;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'staff_type_categories' => ['required', 'array'],
        'staff_type_categories.*' => ['required'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
        'number' => ['required'],
        'filename' => ['nullable', 'image', 'max:4096'],
        'buyed_at' => ['required', 'date'],
        'canceled_at' => ['nullable', 'date'],
    ];

    protected $messages = [
        'staff_type_categories.required' => 'Lütfen staff kategorisini seçiniz.',
        'staff_type_categories.array' => 'Lütfen geçerli bir staff kategorisi seçiniz.',
        'number.required' => 'Staff numarasını yazınız.',
        'filename.image' => 'Staff için dosya seçiniz yazınız.',
        'filename.max' => 'Dosya boyutu en fazla 4 mb olmalıdır.',
        'filename.uploaded' => 'Dosya boyutu en fazla 1 mb olmalıdır.',
        'buyed_at.required' => 'Staff için satın alma tarihi seçiniz yazınız.',
        'buyed_at.date' => 'Staff için geçerli bir satın alma tarihi seçiniz yazınız.',
        'canceled_at.date' => 'Staff için geçerli bir iptal edilme tarihi seçiniz yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.staff.staff-create');
    }

    public function mount(StaffTypeCategory $staffTypeCategory)
    {
        $this->staffTypeCategoryDatas = $staffTypeCategory->query()
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

            if (!is_null($this->filename)) {
                $filename = $this->filename->store(path: 'public/photos');
            }
            $staff = $staffService->create([
                'number' => $this->number,
                'filename' => $filename ?? null,
                'buyed_at' => $this->buyed_at ?? null,
                'canceled_at' => $this->canceled_at ?? null,
                'status' => $this->status == false ? 0 : 1,
            ]);

            foreach ($this->staff_type_categories as $k => $t) {
                DB::insert('insert into staff_type_category_staff_type_staff (staff_type_category_id, staff_type_id, staff_id) values (?, ?, ?)', [$k, $t, $staff->id]);
            }


            $this->dispatch('pg:eventRefresh-StaffTable');
            $msg = 'Staff oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset(['staff_type_categories', 'number', 'filename', 'buyed_at', 'canceled_at']);
        } catch (\Exception $exception) {
            $error = "Staff oluşturulamadı. {$exception->getMessage()}";
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
