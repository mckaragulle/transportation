<?php

namespace App\Livewire\Landlord\StaffTypeCategory;

use App\Services\Landlord\LandlordStaffTypeCategoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class StaffTypeCategoryCreate extends Component
{
    use LivewireAlert;

    public null|string $name;
    public null|string $target;

    public bool $status = true;

    protected LandlordStaffTypeCategoryService $staffTypeCategoryService;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'name' => ['required'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'name.required' => 'Personel kategorisi yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.landlord.staff-type-category.staff-type-category-create');
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(LandlordStaffTypeCategoryService $staffTypeCategoryService)
    {
        $this->validate();
        $this->staffTypeCategoryService = $staffTypeCategoryService;
        DB::beginTransaction();
        try {
            $staffTypeCategory = $this->staffTypeCategoryService->create([
                'name' => $this->name,
                'target' => $this->target,
                'status' => $this->status == false ? 0 : 1,
            ]);

            $this->dispatch('pg:eventRefresh-StaffTypeCategoryTable');
            $msg = 'Personel kategorisi oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset('name');
        } catch (\Exception $exception) {
            $error = "Personel kategorisi oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
