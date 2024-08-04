<?php

namespace App\Livewire\VehicleBrand;

use App\Models\VehicleBrand;
use App\Services\VehicleBrandService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class VehicleBrandEdit extends Component
{
    use LivewireAlert;

    public null|VehicleBrand $vehicleBrand;

    public null|string $name;

    public bool $status = true;

    protected VehicleBrandService $vehicleBrandService;
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
        'name.required' => 'Bayi adını yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null, VehicleBrandService $vehicleBrandService)
    {
        if(!is_null($id)) {

            $this->vehicleBrand =$vehicleBrandService->findById($id);
            $this->name = $this->vehicleBrand->name;
            $this->status = $this->vehicleBrand->status;
        }
        else{
            return $this->redirect(route('vehicleBrands.list'));
        }
    }

    public function render()
    {
        return view('livewire.vehicle-brand.vehicle-brand-edit');
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
            $this->vehicleBrand->name = $this->name;
            $this->vehicleBrand->status = ($this->status == false ? 0 : 1);
            $this->vehicleBrand->save();

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
