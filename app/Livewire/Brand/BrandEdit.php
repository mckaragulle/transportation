<?php

namespace App\Livewire\Brand;

use App\Models\Brand;
use App\Services\BrandService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class BrandEdit extends Component
{
    use LivewireAlert;

    public null|Brand $brand;

    public null|string $name;

    public bool $status = true;

    protected BrandService $brandService;
    /**
     * List of add/edit form rules
     */
    public function rules()
    {
        return [
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
        'name.required' => 'Marka adını yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null, BrandService $brandService)
    {
        if(!is_null($id)) {

            $this->brand =$brandService->findById($id);
            $this->name = $this->brand->name;
            $this->status = $this->brand->status;
        }
        else{
            return $this->redirect(route('brands.list'));
        }
    }

    public function render()
    {
        return view('livewire.brand.brand-edit');
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
            $this->brand->name = $this->name;
            $this->brand->status = ($this->status == false ? 0 : 1);
            $this->brand->save();

            $msg = 'Marka güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Marka güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
