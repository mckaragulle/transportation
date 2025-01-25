<?php

namespace App\Livewire\BrandType;

use App\Models\BrandType;
use App\Services\BrandService;
use App\Services\BrandTypeService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class BrandTypeEdit extends Component
{
    use LivewireAlert;

    public null|array|Collection $brands;
    public null|BrandType $brandType;

    public null|string|int $brand_id;
    public null|string $name;

    public bool $status = true;

    protected BrandTypeService $brandTypeService;
    /**
     * List of add/edit form rules
     */
    public function rules()
    {
        return [
            'brand_id' => [
                'required',
                'exists:brands,id',
            ],
            'name' => [
                'required',
            ],
            'status' => [
                'in:true,false,null,0,1,active,passive,',
                'nullable',
            ],
        ];
    }

    protected $messages = [
        'brand_id.required' => 'Marka adını seçiniz.',
        'brand_id.exists' => 'Lütfen geçerli bir marka adı seçiniz.',
        'name.required' => 'Marka tipi adını yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null, BrandTypeService $brandTypeService, BrandService $brandService)
    {
        if(!is_null($id)) {
            $this->brands = $brandService->all(['id', 'name']);
            $this->brandType =$brandTypeService->findById($id);
            $this->brand_id = $this->brandType->brand_id;
            $this->name = $this->brandType->name;
            $this->status = $this->brandType->status;
        }
        else{
            return $this->redirect(route('brandTypes.list'));
        }
    }

    public function render()
    {
        return view('livewire.brand-type.brand-type-edit');
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
            $this->brandType->brand_id = $this->brand_id;
            $this->brandType->name = $this->name;
            $this->brandType->status = ($this->status == false ? 0 : 1);
            $this->brandType->save();

            $msg = 'Marka tipi güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Marka tipi güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
