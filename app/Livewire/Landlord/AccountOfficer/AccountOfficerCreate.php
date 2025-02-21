<?php

namespace App\Livewire\Landlord\AccountOfficer;

use App\Services\Landlord\LandlordAccountOfficerService;
use App\Services\Landlord\LandlordAccountService;
use App\Services\Landlord\LandlordDealerService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class AccountOfficerCreate extends Component
{
    use LivewireAlert, WithFileUploads;

    public null|Collection $dealers = null;
    public null|Collection $accounts = null;
    public null|string $dealer_id = null;
    public null|string $account_id = null;
    public null|string $number = null;
    public null|string $name = null;
    public null|string $surname = null;
    public null|string $title = null;
    public null|string $phone1 = null;
    public null|string $phone2 = null;
    public null|string $email = null;
    public null|string $detail = null;
    public null|array $files = [];

    public bool $status = true;
    public bool $is_show = false;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'dealer_id' => ['required', 'exists:dealers,id'],
        'account_id' => ['required', 'exists:accounts,id'],
        'number' => ['required'],
        'name' => ['required'],
        'surname' => ['required'],
        'title' => ['required'],
        'phone1' => ['required'],
        'phone2' => ['nullable'],
        'email' => ['nullable', 'email'],
        'detail' => ['nullable'],
        'files' => ['nullable', 'array'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'dealer_id.required' => 'Lütfen bir bayi seçiniz.',
        'dealer_id.exists' => 'Lütfen geçerli bir bayi seçiniz.',
        'account_id.required' => 'Lütfen cari seçiniz yazınız.',
        'account_id.exists' => 'Lütfen geçerli bir cari seçiniz yazınız.',
        'number.required' => 'Lütfen yetkili no\'sunu yazınız.',
        'name.required' => 'Lütfen yetkili adını yazınız.',
        'surname.required' => 'Lütfen yetkili soyadını yazınız.',
        'title.required' => 'Lütfen yetkili ünvanını yazınız.',
        'phone1.required' => 'Lütfen yetkili telefonunu yazınız.',
        'email.email' => 'Lütfen geçerli bir eposta adresi yazınız.',
        'files.array' => 'Lütfen en az 1 tane dosya seçiniz.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.landlord.account-officer.account-officer-create');
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
        $this->account_id = $id;
        $this->is_show = $is_show;

        $this->dealers = $dealerService->all(['id', 'name']);
        $this->accounts = $accountService->all(['id', 'name']);
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(LandlordAccountOfficerService $accountOfficerService)
    {
        $this->validate();
        DB::beginTransaction();
        try {

            $files = null;
            if (!is_null($this->files) && is_array($this->files)) {
                $files = [];
                foreach ($this->files as $file) {
                    $files[] = $file->store(path: 'public/photos');
                }
            }

            $account = $accountOfficerService->create([
                'dealer_id' => $this->dealer_id,
                'account_id' => $this->account_id ?? null,
                'number' => $this->number ?? null,
                'name' => $this->name ?? null,
                'surname' => $this->surname ?? null,
                'title' => $this->title ?? null,
                'phone1' => $this->phone1 ?? null,
                'phone2' => $this->phone2 ?? null,
                'email' => $this->email ?? null,
                'detail' => $this->detail ?? null,
                'files' => $files ?? null,
                'status' => $this->status == false ? 0 : 1,
            ]);

            $this->dispatch('pg:eventRefresh-AccountOfficerTable');
            $msg = 'Cari yetkilisi oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset();
        } catch (\Exception $exception) {
            $error = "Cari yetkilisi oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
