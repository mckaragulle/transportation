<?php

namespace App\Livewire\Landlord\HgsType;

use App\Models\Tenant\HgsType;
use App\Services\HgsTypeCategoryService;
use App\Services\HgsTypeService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class HgsTypeEdit extends Component
{
    use LivewireAlert;

    public null|Collection $hgsTypeCategories;
    public null|Collection $hgsTypes;

    public ?HgsType $hgsType = null;

    public null|int $hgs_type_category_id = null;
    public null|int $hgs_type_id = null;
    public null|string $name;
    public bool $status = true;

    protected HgsTypeCategoryService $hgsTypeCategoryService;
    protected HgsTypeService $hgsTypeService;
    /**
     * List of add/edit form rules
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
            ],
            'hgs_type_category_id' => [
                'required',
                'exists:hgs_type_categories,id',
            ],
            'hgs_type_id' => [
                'nullable',
                'exists:hgs_types,id',
            ],
            'status' => [
                'in:true,false,null,0,1,active,passive,',
                'nullable',
            ],
        ];
    }

    protected $messages = [
        'hgs_type_category_id.required' => 'Lütfen cari kategorisini seçiniz.',
        'hgs_type_category_id.exists' => 'Lütfen geçerli bir cari kategorisi seçiniz.',
        'hgs_type_id.exists' => 'Lütfen geçerli bir cari seçiniz.',
        'name.required' => 'Hgs adını yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null, HgsTypeCategoryService $hgsTypeCategoryService, HgsTypeService $hgsTypeService)
    {
        if (!is_null($id)) {
            $this->hgsType = $hgsTypeService->findById($id);
            $this->hgs_type_category_id = $this->hgsType->hgs_type_category_id;
            $this->hgs_type_id = $this->hgsType->hgs_type_id??null;
            $this->name = $this->hgsType->name??null;
            $this->status = $this->hgsType->status;
            $this->hgsTypeCategories = $hgsTypeCategoryService->all();
            $this->hgsTypes = HgsType::query()
                ->where(['hgs_type_category_id' => $this->hgs_type_category_id])
                ->with('hgs_type')
                ->orderBy('id')
                ->get();

        } else {
            return $this->redirect(route('hgsTypes.list'));
        }
    }

    public function render()
    {
        return view('livewire.landlord.hgs-type.hgs-type-edit');
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
            $this->hgsType->hgs_type_category_id = $this->hgs_type_category_id;
            $this->hgsType->hgs_type_id = $this->hgs_type_id ?? null;
            $this->hgsType->name = $this->name;
            $this->hgsType->status = $this->status == false ? 0 : 1;
            $this->hgsType->save();

            $msg = 'Hgs güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Hgs güncellenemedi. {$exception->getMessage()}";
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
