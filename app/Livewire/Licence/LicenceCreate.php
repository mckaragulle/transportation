<?php

namespace App\Livewire\Licence;

use App\Models\LicenceType;
use App\Models\LicenceTypeCategory;
use App\Services\LicenceService;
use App\Services\LicenceTypeCategoryService;
use App\Services\LicenceTypeService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class LicenceCreate extends Component
{
    use LivewireAlert, WithFileUploads;

    public null|array $licence_type_categories = [];
    public null|Collection $licenceTypeCategoryDatas;
    public null|Collection $licences;
    public null|string $number = null;
    public null|string $started_at = null;
    public null|string $finished_at = null;
    public null|string $detail = null;
    public $filename;

    public bool $status = true;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'licence_type_categories' => ['required', 'array'],
        'licence_type_categories.*' => ['required'],
        'number' => ['required'],
        'started_at' => ['required', 'date'],
        'finished_at' => ['nullable', 'date'],
        'detail' => ['nullable'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
        'filename' => ['nullable', 'image', 'max:4096'],
    ];

    protected $messages = [
        'licence_type_categories.required' => 'Lütfen sürücü belgesi kategorisini seçiniz.',
        'licence_type_categories.array' => 'Lütfen geçerli bir sürücü belgesi kategorisi seçiniz.',
        'number.required' => 'Sürücü belgesi numarasını yazınız.',
        'started_at.required' => 'Alınma tarihi seçiniz yazınız.',
        'started_at.date' => 'Lütfen geçerli bir alınma tarihi seçiniz yazınız.',
        'finished_at.required' => 'Bitiş tarihi seçiniz yazınız.',
        'finished_at.date' => 'Lütfen geçerli bir bitiş tarihi seçiniz yazınız.',
        'filename.image' => 'Müşteri için dosya seçiniz yazınız.',
        'filename.max' => 'Dosya boyutu en fazla 4 mb olmalıdır.',
        'filename.uploaded' => 'Dosya boyutu en fazla 4 mb olmalıdır.',
        
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.licence.licence-create');
    }

    public function mount(LicenceTypeCategory $licenceTypeCategory)
    {
        $this->licenceTypeCategoryDatas = $licenceTypeCategory->query()
        ->with(['licence_types:id,licence_type_category_id,licence_type_id,name', 'licence_types.licence_types:id,licence_type_category_id,licence_type_id,name'])
        ->get(['id', 'name']);
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(LicenceService $licenceService)
    {
        $this->validate();
        DB::beginTransaction();
        try {

            if(!is_null($this->filename)){
                $filename = $this->filename->store(path: 'public/photos');
            }
            $licence = $licenceService->create([
                'number' => $this->number,
                'started_at' => $this->started_at,
                'finished_at' => $this->finished_at,
                'detail' => $this->detail,
                'filename' => $filename ?? null,
                'status' => $this->status == false ? 0 : 1,
            ]);

            foreach($this->licence_type_categories as $k => $t)
            {
                DB::insert('insert into licence_type_category_licence_type_licence (licence_type_category_id, licence_type_id, licence_id) values (?, ?, ?)', [$k, $t, $licence->id]);
            }

            $this->dispatch('pg:eventRefresh-LicenceTable');
            $msg = 'Sürücü belgesi oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset();
        } catch (\Exception $exception) {
            $error = "Sürücü belgesi oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }

    public function updated()
    {
        $this->validate();    
    }
}