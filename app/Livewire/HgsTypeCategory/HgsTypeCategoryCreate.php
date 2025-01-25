<?php

namespace App\Livewire\HgsTypeCategory;

use App\Services\HgsTypeCategoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class HgsTypeCategoryCreate extends Component
{
    use LivewireAlert;

    public null|string $name;

    public bool $status = true;

    protected HgsTypeCategoryService $hgsTypeCategoryService;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'name' => ['required'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'name.required' => 'Hgs kategorisi yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.hgs-type-category.hgs-type-category-create');
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(HgsTypeCategoryService $hgsTypeCategoryService)
    {
        $this->validate();
        $this->hgsTypeCategoryService = $hgsTypeCategoryService;
        DB::beginTransaction();
        try {
            $hgsTypeCategory = $this->hgsTypeCategoryService->create([
                'name' => $this->name,
                'status' => $this->status == false ? 0 : 1,
            ]);

            $this->dispatch('pg:eventRefresh-HgsTypeCategoryTable');
            $msg = 'Hgs kategorisi oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset('name');
        } catch (\Exception $exception) {
            $error = "Hgs kategorisi oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
