<?php

namespace App\Livewire\Fined;

use App\Services\FinedService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class FinedCreate extends Component
{
    use LivewireAlert;

    public null|string $number;
    public null|string $detail;

    public bool $is_default = true;
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

    public function render()
    {
        return view('livewire.fined.fined-create');
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(FinedService $finedService)
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $fined = $finedService->create([
                'number' => $this->number,
                'detail' => $this->detail,
                'status' => $this->status == false ? 0 : 1,
            ]);

            $this->dispatch('pg:eventRefresh-FinedTable');
            $msg = 'Araç cezası oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset();
        } catch (\Exception $exception) {
            $error = "Araç cezası oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
