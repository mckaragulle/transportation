<?php

namespace App\Livewire\Landlord\DealerOfficer;

use App\Services\Landlord\LandlordDealerOfficerService;
use Illuminate\Database\Eloquent\Casts\ArrayObject;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class DealerOfficerEdit extends Component
{
    use LivewireAlert, WithFileUploads;

    public ?DealerOfficer $dealerOfficer = null;
    public null|string $dealer_id = null;
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
        'number.required' => 'Lütfen yetkili no\'sunu yazınız.',
        'name.required' => 'Lütfen yetkili adını yazınız.',
        'surname.required' => 'Lütfen yetkili soyadını yazınız.',
        'title.required' => 'Lütfen yetkili ünvanını yazınız.',
        'phone1.required' => 'Lütfen yetkili telefonunu yazınız.',
        'email.email' => 'Lütfen geçerli bir eposta adresi yazınız.',
        'files.array' => 'Lütfen en az 1 tane dosya seçiniz.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null, LandlordDealerOfficerService $dealerOfficerService)
    {
        if (!is_null($id)) {
            $this->dealerOfficer = $dealerOfficerService->findById($id);
            $this->dealer_id = $this->dealerOfficer->dealer_id;
            $this->number = $this->dealerOfficer->number;
            $this->name = $this->dealerOfficer->name;
            $this->surname = $this->dealerOfficer->surname;
            $this->title = $this->dealerOfficer->title;
            $this->phone1 = $this->dealerOfficer->phone1;
            $this->phone2 = $this->dealerOfficer->phone2;
            $this->email = $this->dealerOfficer->email;
            $this->detail = $this->dealerOfficer->detail;
            $this->status = $this->dealerOfficer->status;

            if (isset($this->dealerOfficer?->files) && is_array($this->dealerOfficer?->files)) {
                foreach ($this->dealerOfficer?->files as $file) {
                    if (Storage::exists($file)) {
                        $this->oldfiles[] = $file;
                    }
                }
            }
        } else {
            return $this->redirect(route('dealer_officers.list'));
        }
    }

    public function render()
    {
        return view('livewire.landlord.dealer-officer.dealer-officer-edit');
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
            $this->dealerOfficer->number = $this->number;
            $this->dealerOfficer->name = $this->name;
            $this->dealerOfficer->surname = $this->surname;
            $this->dealerOfficer->title = $this->title;
            $this->dealerOfficer->phone1 = $this->phone1;
            $this->dealerOfficer->phone2 = $this->phone2;
            $this->dealerOfficer->email = $this->email ?? null;
            $this->dealerOfficer->detail = $this->detail ?? null;

            $files = null;

            if (!is_null($this->files) && is_array($this->files)) {
                $files = [];
                foreach ($this->files as $file) {
                    $files[] = $file->store(path: 'public/photos');
                }
                $this->dealerOfficer->files = $files;
            }


            $this->dealerOfficer->status = $this->status == false ? 0 : 1;
            $this->dealerOfficer->save();

            $msg = 'Bayi yetkilisi güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Bayi yetkilisi güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
