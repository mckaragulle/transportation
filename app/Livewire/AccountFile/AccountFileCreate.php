<?php

namespace App\Livewire\AccountFile;

use App\Services\AccountFileService;
use App\Services\AccountService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class AccountFileCreate extends Component
{
    use LivewireAlert, WithFileUploads;

    public null|Collection $accounts = null;
    public null|int $account_id = null;
    public $filename;
    public null|string $title = null;

    public bool $status = true;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'account_id' => ['required', 'exists:accounts,id'],
        'filename' => ['nullable', 'max:4096'],
        'title' => ['required'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'account_id.required' => 'Lütfen cari seçiniz yazınız.',
        'account_id.exists' => 'Lütfen geçerli bir cari seçiniz yazınız.',
        'title.required' => 'Lütfen dosya adını yazınız.',
        'filename.required' => 'Lütfen 1 adet dosya seçiniz.',
        'filename.max' => 'Dosya boyutu en fazla 4 mb olmalıdır.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.account-file.account-file-create');
    }

    public function mount(AccountService $accountService)
    {
        $this->accounts = $accountService->all(['id', 'name']);
       
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(AccountFileService $accountFileService)
    {
        $this->validate();
        DB::beginTransaction();
        try {
            
            if(!is_null($this->filename)){
                $filename = $this->filename->store(path: 'public/photos');
            }

            $account = $accountFileService->create([
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