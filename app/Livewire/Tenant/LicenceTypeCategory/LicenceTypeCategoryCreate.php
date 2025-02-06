<?php

namespace App\Livewire\Tenant\LicenceTypeCategory;

use App\Services\LicenceTypeCategoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class LicenceTypeCategoryCreate extends Component
{
    use LivewireAlert;

    public null|string $name;

    public bool $status = true;

    protected LicenceTypeCategoryService $licenceTypeCategoryService;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'name' => ['required'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'name.required' => 'Sürücü belgesi kategorisini yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.tenant.licence-type-category.licence-type-category-create');
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(LicenceTypeCategoryService $licenceTypeCategoryService)
    {
        $this->validate();
        $this->licenceTypeCategoryService = $licenceTypeCategoryService;
        DB::beginTransaction();
        try {
            $licenceTypeCategory = $this->licenceTypeCategoryService->create([
                'name' => $this->name,
                'status' => $this->status == false ? 0 : 1,
            ]);

            $this->dispatch('pg:eventRefresh-LicenceTypeCategoryTable');
            $msg = 'Sürücü belgesi kategorisi oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset('name');
        } catch (\Exception $exception) {
            $error = "Sürücü belgesi kategorisi oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
