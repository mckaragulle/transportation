<?php

namespace App\Livewire\Landlord\AccountOfficer;

use App\Services\Landlord\LandlordAccountOfficerService;
use App\Services\Landlord\LandlordAccountService;
use App\Services\Landlord\LandlordDealerService;
use Illuminate\Database\Eloquent\Casts\ArrayObject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class AccountOfficerEdit extends Component
{
    use LivewireAlert, WithFileUploads;

    public ?Model $accountOfficer = null;
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
    public null|array|ArrayObject $oldfiles = null;

    public bool $status = true;

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

    public function mount($id = null,
                          LandlordDealerService $dealerService,
                          LandlordAccountService $accountService,
                          LandlordAccountOfficerService $accountOfficerService
    )
    {
        if (!is_null($id)) {
            $this->accountOfficer = $accountOfficerService->findById($id);
            $this->dealers = $dealerService->all(['id', 'name']);
            $this->accounts = $accountService->all(['id', 'name']);
            if (auth()->getDefaultDriver() == 'dealer') {
                $this->dealer_id = auth()->user()->id;
            } else if (auth()->getDefaultDriver() == 'users') {
                $this->dealer_id = auth()->user()->dealer()->id;
            }

            $this->account_id = $this->accountOfficer->account_id;
            $this->number = $this->accountOfficer->number;
            $this->name = $this->accountOfficer->name;
            $this->surname = $this->accountOfficer->surname;
            $this->title = $this->accountOfficer->title;
            $this->phone1 = $this->accountOfficer->phone1;
            $this->phone2 = $this->accountOfficer->phone2;
            $this->email = $this->accountOfficer->email;
            $this->detail = $this->accountOfficer->detail;
            $this->status = $this->accountOfficer->status;

            if (isset($this->accountOfficer?->files) && is_array($this->accountOfficer?->files)) {
                foreach ($this->accountOfficer?->files as $file) {
                    if (Storage::exists($file)) {
                        $this->oldfiles[] = $file;
                    }
                }
            }
        } else {
            return $this->redirect(route('account_officers.list'));
        }
    }

    public function render()
    {
        return view('livewire.landlord.account-officer.account-officer-edit');
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

            $this->accountOfficer->dealer_id = $this->dealer_id;
            $this->accountOfficer->account_id = $this->account_id;
            $this->accountOfficer->number = $this->number;
            $this->accountOfficer->name = $this->name;
            $this->accountOfficer->surname = $this->surname;
            $this->accountOfficer->title = $this->title;
            $this->accountOfficer->phone1 = $this->phone1;
            $this->accountOfficer->phone2 = $this->phone2;
            $this->accountOfficer->email = $this->email ?? null;
            $this->accountOfficer->detail = $this->detail ?? null;

            $files = null;

            if (!is_null($this->files) && is_array($this->files)) {
                $files = [];
                foreach ($this->files as $file) {
                    $files[] = $file->store(path: 'public/photos');
                }
                $this->accountOfficer->files = $files;
            }


            $this->accountOfficer->status = $this->status == false ? 0 : 1;
            $this->accountOfficer->save();

            $msg = 'Cari yetkilisi güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Cari yetkilisi güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
