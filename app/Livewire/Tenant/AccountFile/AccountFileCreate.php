<?php

namespace App\Livewire\Tenant\AccountFile;

use App\Services\Tenant\AccountFileService;
use App\Services\Tenant\AccountService;
use App\Services\Tenant\DealerService;
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

    public null|string $account_id = null;
    public $filename;
    public null|string $title = null;

    public bool $status = true;
    public bool $is_show = false;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'account_id' => ['required', 'exists:tenant.accounts,id'],
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
        return view('livewire.tenant.account-file.account-file-create');
    }

    public function mount(null|string $id = null, bool $is_show, AccountService $accountService)
    {

        $this->account_id = $id;
        $this->is_show = $is_show;

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

            if ($this->filename != null) {
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
