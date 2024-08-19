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

    public null|Collection $hgsTypeCategories;
    public null|Collection $hgsTypes;
    public null|Collection $hgses;
    public null|string|int $hgs_type_category_id = null;
    public null|string|int $hgs_type_id = null;
    public null|int $number;
    public $filename;
    public null|string $buyed_at;
    public null|string $canceled_at;
    

    public bool $status = true;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'hgs_type_category_id' => ['required', 'exists:hgs_type_categories,id'],
        'hgs_type_id' => ['required', 'exists:hgs_types,id'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
        'number' => ['required'],
        'filename' => ['nullable', 'image', 'max:1024'],
        'buyed_at' => ['required', 'date'],
        'canceled_at' => ['nullable', 'date'],
    ];

    protected $messages = [
        'hgs_type_category_id.required' => 'Lütfen hgs kategorisini seçiniz.',
        'hgs_type_category_id.exists' => 'Lütfen geçerli bir hgs kategorisi seçiniz.',
        'hgs_type_id.required' => 'Lütfen hgs tipi seçiniz.',
        'hgs_type_id.exists' => 'Lütfen geçerli bir hgs tipi seçiniz.',
        'number.required' => 'Hgs numarasını yazınız.',
        'filename.image' => 'Hgs için dosya seçiniz yazınız.',
        'filename.max' => 'Dosya boyutu en fazla 1 mb olmalıdır.',
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

    public function mount(HgsTypeCategoryService $hgsTypeCategoryService)
    {
        $this->hgsTypeCategories = $hgsTypeCategoryService->all(['id', 'name']);
        $this->hgsTypes = HgsType::query()->where(['hgs_type_category_id' => $this->hgs_type_category_id])->with('hgs_type')->orderBy('id')->get(['id', 'hgs_type_id', 'name']);
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
            $hgsType = $hgsService->create([
                'hgs_type_category_id' => $this->hgs_type_category_id ?? null,
                'hgs_type_id' => $this->hgs_type_id ?? null,
                'number' => $this->number,
                'filename' => $filename ?? null,
                'buyed_at' => $this->buyed_at ?? null,
                'canceled_at' => $this->canceled_at ?? null,
                'status' => $this->status == false ? 0 : 1,
            ]);

            $this->dispatch('pg:eventRefresh-HgsTable');
            $msg = 'Hgs oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset();
        } catch (\Exception $exception) {
            $error = "Hgs oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }

    public function updatedHgsTypeCategoryId()
    {
        $this->hgsTypes = HgsType::query()->where(['hgs_type_category_id' => $this->hgs_type_category_id])->with('hgs_type')->orderBy('id')->get(['id', 'hgs_type_id', 'name']);
    }
}
