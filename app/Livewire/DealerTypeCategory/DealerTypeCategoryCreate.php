<?php

namespace App\Livewire\DealerTypeCategory;

use App\Services\DealerTypeCategoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class DealerTypeCategoryCreate extends Component
{
    use LivewireAlert;

    public null|string $name;

    public bool $is_required = true;
    public bool $is_multiple = false;
    public bool $status = true;

    protected DealerTypeCategoryService $dealerTypeCategoryService;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'name' => ['required'],
        'is_required' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
        'is_multiple' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'name.required' => 'Cari kategorisi yazınız.',
        'is_required.in' => 'Lütfen geçerli bir durum seçiniz.',
        'is_multiple.in' => 'Lütfen geçerli bir durum seçiniz.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.dealer-type-category.dealer-type-category-create');
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(DealerTypeCategoryService $dealerTypeCategoryService)
    {
        $this->validate();
        $this->dealerTypeCategoryService = $dealerTypeCategoryService;
        DB::beginTransaction();
        try {
            $dealerTypeCategory = $this->dealerTypeCategoryService->create([
                'name' => $this->name,
                'is_required' => $this->is_required == false ? 0 : 1,
                'is_multiple' => $this->is_multiple == false ? 0 : 1,
                'status' => $this->status == false ? 0 : 1,
            ]);

            $this->dispatch('pg:eventRefresh-DealerTypeCategoryTable');
            $msg = 'Cari kategorisi oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset('name');
        } catch (\Exception $exception) {
            $error = "Cari kategorisi oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
