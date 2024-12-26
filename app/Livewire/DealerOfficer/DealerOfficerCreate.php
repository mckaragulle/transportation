<?php

namespace App\Livewire\DealerOfficer;

use App\Services\DealerOfficerService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class DealerOfficerCreate extends Component
{
    use LivewireAlert, WithFileUploads;
    public null|int $dealer_id = null;
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

    public function render()
    {
        return view('livewire.dealer-officer.dealer-officer-create');
    }

    public function mount(null|int $id = null, bool $is_show)
    {       
        $this->dealer_id = $id > 0 ? $id : null;
        $this->is_show = $is_show;
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(DealerOfficerService $dealerOfficerService)
    {
        $this->validate();
        DB::beginTransaction();
        try {
            
            $files = null;
            if(!is_null($this->files) && is_array($this->files)){
                $files = [];
                foreach($this->files as $file){
                    $files[] = $file->store(path: 'public/photos');
                }
            }

            $dealer = $dealerOfficerService->create([
                'dealer_id' => $this->dealer_id,
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

            $this->dispatch('pg:eventRefresh-DealerOfficerTable');
            $msg = 'Bayi yetkilisi oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset();
        } catch (\Exception $exception) {
            $error = "Bayi yetkilisi oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}