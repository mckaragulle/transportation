<?php

namespace App\Livewire\Tenant\Fined;

use App\Services\FinedService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class FinedEdit extends Component
{
    use LivewireAlert;

    public null|Collection $vehicleBrands;

    public $fined;

    public null|string $number;
    public null|string $detail;
    public bool $status = true;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'number' => ['required'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'number.required' => 'Araç ceza numarasını yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null, FinedService $finedService)
    {
        if (!is_null($id)) {
            $this->fined = $finedService->findById($id);
            $this->number = $this->fined->number;
            $this->detail = $this->fined->detail;
            $this->status = $this->fined->status;
        } else {
            return $this->redirect(route('fineds.list'));
        }
    }

    public function render()
    {
        return view('livewire.tenant.fined.fined-edit');
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
            $this->fined->number = $this->number;
            $this->fined->detail = $this->detail;
            $this->fined->status = $this->status == false ? 0 : 1;
            $this->fined->save();

            $msg = 'Araç cezası güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Araç cezası güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
