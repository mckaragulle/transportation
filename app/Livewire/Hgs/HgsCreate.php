<?php

namespace App\Livewire\Hgs;

use App\Models\HgsType;
use App\Models\HgsTypeCategory;
use App\Services\HgsService;
use App\Services\HgsTypeCategoryService;
use App\Services\HgsTypeService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class HgsCreate extends Component
{
    use LivewireAlert, WithFileUploads;

    public null|array $hgs_type_categories = [];
    public null|Collection $hgsTypeCategoryDatas;
    public null|Collection $hgses;
    public null|int $number;
    public $filename;
    public null|string $buyed_at;
    public null|string $canceled_at;
    

    public bool $status = true;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'hgs_type_categories' => ['required', 'array'],
        'hgs_type_categories.*' => ['required'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
        'number' => ['required'],
        'filename' => ['nullable', 'image', 'max:4096'],
        'buyed_at' => ['required', 'date'],
        'canceled_at' => ['nullable', 'date'],
    ];

    protected $messages = [
        'hgs_type_categories.required' => 'Lütfen hgs kategorisini seçiniz.',
        'hgs_type_categories.array' => 'Lütfen geçerli bir hgs kategorisi seçiniz.',
        'number.required' => 'Hgs numarasını yazınız.',
        'filename.image' => 'Hgs için dosya seçiniz yazınız.',
        'filename.max' => 'Dosya boyutu en fazla 4 mb olmalıdır.',
        'filename.uploaded' => 'Dosya boyutu en fazla 1 mb olmalıdır.',
        'buyed_at.required' => 'Hgs için satın alma tarihi seçiniz yazınız.',
        'buyed_at.date' => 'Hgs için geçerli bir satın alma tarihi seçiniz yazınız.',
        'canceled_at.date' => 'Hgs için geçerli bir iptal edilme tarihi seçiniz yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.hgs.hgs-create');
    }

    public function mount(HgsTypeCategory $hgsTypeCategory)
    {
        $this->hgsTypeCategoryDatas = $hgsTypeCategory->query()
        ->with(['hgs_types:id,hgs_type_category_id,hgs_type_id,name', 'hgs_types.hgs_types:id,hgs_type_category_id,hgs_type_id,name'])
        ->get(['id', 'name']);
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(HgsService $hgsService)
    {
        $this->validate();
        DB::beginTransaction();
        try {

            if(!is_null($this->filename)){
                $filename = $this->filename->store(path: 'public/photos');
            }
            $hgs = $hgsService->create([
                'number' => $this->number,
                'filename' => $filename ?? null,
                'buyed_at' => $this->buyed_at ?? null,
                'canceled_at' => $this->canceled_at ?? null,
                'status' => $this->status == false ? 0 : 1,
            ]);

            foreach($this->hgs_type_categories as $k => $t)
            {
                DB::insert('insert into hgs_type_category_hgs_type_hgs (hgs_type_category_id, hgs_type_id, hgs_id) values (?, ?, ?)', [$k, $t, $hgs->id]);
            }


            $this->dispatch('pg:eventRefresh-HgsTable');
            $msg = 'Hgs oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset(['hgs_type_categories', 'number', 'filename', 'buyed_at', 'canceled_at']);
        } catch (\Exception $exception) {
            $error = "Hgs oluşturulamadı. {$exception->getMessage()}";
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