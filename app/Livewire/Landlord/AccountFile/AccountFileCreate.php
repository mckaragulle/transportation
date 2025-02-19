<?php

namespace App\Livewire\Landlord\AccountFile;

use App\Services\Landlord\LandlordAccountFileService;
use App\Services\Landlord\LandlordAccountService;
use App\Services\Landlord\LandlordDealerService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class AccountFileCreate extends Component
{
    use LivewireAlert, WithFileUploads;

    public null|Collection $dealers = null;
    public null|Collection $accounts = null;
    public null|string $dealer_id = null;
    public null|string $account_id = null;
    public $filename;
    public null|string $title = null;

    public bool $status = true;
    public bool $is_show = false;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'dealer_id' => ['required', 'exists:dealers,id'],
        'account_id' => ['required', 'exists:accounts,id'],
        'filename' => ['nullable', 'max:4096'],
        'title' => ['required'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'dealer_id.required' => 'Lütfen bir bayi seçiniz.',
        'dealer_id.exists' => 'Lütfen geçerli bir bayi seçiniz.',
        'account_id.required' => 'Lütfen cari seçiniz yazınız.',
        'account_id.exists' => 'Lütfen geçerli bir cari seçiniz yazınız.',
        'title.required' => 'Lütfen dosya adını yazınız.',
        'filename.required' => 'Lütfen 1 adet dosya seçiniz.',
        'filename.max' => 'Dosya boyutu en fazla 4 mb olmalıdır.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.landlord.account-file.account-file-create');
    }

    public function mount(null|string $id = null,
                          bool $is_show,
                          LandlordDealerService $dealerService,
                          LandlordAccountService $accountService
    )
    {
        if (auth()->getDefaultDriver() == 'dealer') {
            $this->dealer_id = auth()->user()->id;
        } else if (auth()->getDefaultDriver() == 'users') {
            $this->dealer_id = auth()->user()->dealer()->id;
        }
        $this->account_id = $id > 0 ? $id : null;
        $this->is_show = $is_show;

        $this->dealers = $dealerService->all(['id', 'name']);
        $this->accounts = $accountService->all(['id', 'name']);
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(LandlordAccountFileService $accountFileService)
    {
        $this->validate();
        DB::beginTransaction();
        try {

            if ($this->filename != null) {
                $filename = $this->filename->store(path: 'public/photos');
            }

            $account = $accountFileService->create([
                'dealer_id' => $this->dealer_id,
                'account_id' => $this->account_id ?? null,
                'title' => $this->title ?? null,
                'filename' => $filename ?? null,
                'status' => $this->status == false ? 0 : 1,
            ]);

            $this->dispatch('pg:eventRefresh-AccountFileTable');
            $msg = 'Cari dosyası oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset();
        } catch (\Exception $exception) {
            $error = "Cari dosyası oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
