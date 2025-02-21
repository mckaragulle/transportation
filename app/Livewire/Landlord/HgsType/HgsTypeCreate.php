<?php

namespace App\Livewire\Landlord\HgsType;

use App\Models\Landlord\LandlordHgsType;
use App\Services\Landlord\LandlordHgsTypeCategoryService;
use App\Services\Landlord\LandlordHgsTypeService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class HgsTypeCreate extends Component
{
    use LivewireAlert;

    public null|Collection $hgsTypeCategories;
    public null|Collection $hgsTypes;
    public null|string|int $hgs_type_category_id = null;
    public null|string|int $hgs_type_id = null;
    public null|string $name;

    public bool $status = true;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'hgs_type_category_id' => ['required', 'exists:hgs_type_categories,id'],
        'hgs_type_id' => ['nullable', 'exists:hgs_types,id'],
        'name' => ['required'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'hgs_type_category_id.required' => 'Lütfen cari kategorisini seçiniz.',
        'hgs_type_category_id.exists' => 'Lütfen geçerli bir cari kategorisi seçiniz.',
        'hgs_type_id.exists' => 'Lütfen geçerli bir cari seçiniz.',
        'name.required' => 'Hgs adını yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.landlord.hgs-type.hgs-type-create');
    }

    public function mount(LandlordHgsTypeCategoryService $hgsTypeCategoryService)
    {
        $this->hgsTypeCategories = $hgsTypeCategoryService->all(['id', 'name']);
        $this->hgsTypes = LandlordHgsType::query()
            ->where(['hgs_type_category_id' => $this->hgs_type_category_id])
            ->whereDoesntHave('hgs_types')
            ->with('hgs_type')
            ->orderBy('id')
            ->get(['id', 'hgs_type_id', 'name']);
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(LandlordHgsTypeService $hgsTypeService)
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $hgsType = $hgsTypeService->create([
                'hgs_type_category_id' => $this->hgs_type_category_id ?? null,
                'hgs_type_id' => $this->hgs_type_id ?? null,
                'name' => $this->name,
                'status' => $this->status == false ? 0 : 1,
            ]);

            $this->dispatch('pg:eventRefresh-HgsTypeTable');
            $msg = 'Hgs oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            $this->hgsTypes = LandlordHgsType::query()
                ->where(['hgs_type_category_id' => $this->hgs_type_category_id])
                ->whereDoesntHave('hgs_types')
                ->with('hgs_type')
                ->orderBy('id')
                ->get(['id', 'hgs_type_id', 'name']);
            DB::commit();
            $this->reset(['name', 'hgs_type_id']);

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
        $this->hgsTypes = LandlordHgsType::query()->where(['hgs_type_category_id' => $this->hgs_type_category_id])->with('hgs_type')->orderBy('id')->get(['id', 'hgs_type_id', 'name']);
    }
}
