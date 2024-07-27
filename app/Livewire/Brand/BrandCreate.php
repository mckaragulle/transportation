<?php

namespace App\Livewire\Brand;

use App\Services\BrandService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class BrandCreate extends Component
{
    use LivewireAlert;

    public null|string $name;

    public bool $status = true;

    protected BrandService $brandService;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'name' => ['required'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'name.required' => 'Marka adını yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.brand.brand-create');
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(BrandService $brandService)
    {
        $this->validate();
        $this->brandService = $brandService;
        DB::beginTransaction();
        try {
            $brand = $this->brandService->create([
                'name' => $this->name,
                'status' => $this->status == false ? 0 : 1,
            ]);

            $this->dispatch('pg:eventRefresh-BrandTable');
            $msg = 'Yeni marka oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset('name');
        } catch (\Exception $exception) {
            $error = "Marka oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
