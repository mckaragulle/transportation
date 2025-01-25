<?php

namespace App\Livewire\BrandType;

use App\Services\BrandService;
use App\Services\BrandTypeService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class BrandTypeCreate extends Component
{
    use LivewireAlert;

    public null|array|Collection $brands;
    public null|string|int $brand_id;
    public null|string $name;

    public bool $status = true;

    protected BrandTypeService $brandTypeService;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'brand_id' => ['required', 'exists:brands,id'],
        'name' => ['required'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'brand_id.required' => 'Marka adını seçiniz.',
        'brand_id.exists' => 'Lütfen geçerli bir marka adı seçiniz.',
        'name.required' => 'Marka tipi adını yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount(BrandService $brandService)
    {
        $this->brands = $brandService->all(['id', 'name']);
    }

    public function render()
    {
        return view('livewire.brand-type.brand-type-create');
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(BrandTypeService $brandTypeService)
    {
        $this->validate();
        $this->brandTypeService = $brandTypeService;
        DB::beginTransaction();
        try {
            $brand = $this->brandTypeService->create([
                'brand_id' => $this->brand_id,
                'name' => $this->name,
                'status' => $this->status == false ? 0 : 1,
            ]);

            $this->dispatch('pg:eventRefresh-BrandTypeTable');
            $msg = 'Yeni marka tipi oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset('name');
        } catch (\Exception $exception) {
            $error = "Marka tipi oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
