<?php

namespace App\Livewire\DealerLogo;

use App\Services\DealerLogoService;
use App\Services\DealerService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class DealerLogoCreate extends Component
{
    use LivewireAlert, WithFileUploads;

    public null|string $dealer_id = null;
    public null|string $title = null;
    public $filename;

    public bool $status = true;
    public bool $is_show = false;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'dealer_id' => ['required', 'exists:dealers,id'],
        'filename' => ['required', 'max:4096'],
        'title' => ['required'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'dealer_id.required' => 'Lütfen bir bayi seçiniz.',
        'dealer_id.exists' => 'Lütfen geçerli bir bayi seçiniz.',
        'title.required' => 'Lütfen dosya adını yazınız.',
        'filename.required' => 'Lütfen 1 adet dosya seçiniz.',
        'filename.max' => 'Dosya boyutu en fazla 4 mb olmalıdır.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.dealer-logo.dealer-logo-create');
    }

    public function mount(null|string $id = null, bool $is_show, DealerService $dealerService)
    {
        $this->dealer_id = $id;
        $this->is_show = $is_show;
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(DealerLogoService $dealerLogoService)
    {
        $this->validate();
        DB::beginTransaction();
        try {

            if ($this->filename != null) {
                $filename = $this->filename->store(path: 'public/photos');
            }
            $dealerLogoService->where(['dealer_id' => $this->dealer_id])->update(['status' => false]);

            $dealer = $dealerLogoService->create([
                'dealer_id' => $this->dealer_id,
                'title' => $this->title ?? null,
                'filename' => $filename ?? null,
                'status' => $this->status == false ? 0 : 1,
            ]);



            $this->dispatch('pg:eventRefresh-DealerLogoTable');
            $msg = 'Bayi logosu oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset();
        } catch (\Exception $exception) {
            $error = "Bayi logosu oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
