<?php

namespace App\Livewire\Tenant\StaffFile;

use App\Services\Tenant\StaffFileService;
use App\Services\Tenant\StaffService;
use App\Services\Tenant\DealerService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class StaffFileCreate extends Component
{
    use LivewireAlert, WithFileUploads;

    public null|Collection $staffs = null;

    public null|string $staff_id = null;
    public $filename;
    public null|string $title = null;

    public bool $status = true;
    public bool $is_show = false;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'staff_id' => ['required', 'exists:tenant.staffs,id'],
        'filename' => ['nullable', 'max:4096'],
        'title' => ['required'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'staff_id.required' => 'Lütfen personel seçiniz yazınız.',
        'staff_id.exists' => 'Lütfen geçerli bir personel seçiniz yazınız.',
        'title.required' => 'Lütfen dosya adını yazınız.',
        'filename.required' => 'Lütfen 1 adet dosya seçiniz.',
        'filename.max' => 'Dosya boyutu en fazla 4 mb olmalıdır.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.tenant.staff-file.staff-file-create');
    }

    public function mount(null|string $id = null, bool $is_show, StaffService $staffService)
    {

        $this->staff_id = $id;
        $this->is_show = $is_show;

        $this->staffs = $staffService->all(['id', 'name']);
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(StaffFileService $staffFileService)
    {
        $this->validate();
        DB::beginTransaction();
        try {

            if ($this->filename != null) {
                $filename = $this->filename->store(path: 'public/photos');
            }

            $staff = $staffFileService->create([
                'staff_id' => $this->staff_id ?? null,
                'title' => $this->title ?? null,
                'filename' => $filename ?? null,
                'status' => $this->status == false ? 0 : 1,
            ]);

            $this->dispatch('pg:eventRefresh-StaffFileTable');
            $msg = 'Personel dosyası oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset();
        } catch (\Exception $exception) {
            $error = "Personel dosyası oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
