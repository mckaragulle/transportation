<?php

namespace App\Livewire\Tenant\LicenceType;

use App\Models\Tenant\LicenceType;
use App\Services\LicenceTypeCategoryService;
use App\Services\LicenceTypeService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class LicenceTypeCreate extends Component
{
    use LivewireAlert;

    public null|Collection $licenceTypeCategories;
    public null|Collection $licenceTypes;
    public null|string|int $licence_type_category_id = null;
    public null|string|int $licence_type_id = null;
    public null|string $name;

    public bool $status = true;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'licence_type_category_id' => ['required', 'exists:landlord.licence_type_categories,id'],
        'licence_type_id' => ['nullable', 'exists:landlord.licence_types,id'],
        'name' => ['required'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'licence_type_category_id.required' => 'Lütfen sürücü belgesi kategorisini seçiniz.',
        'licence_type_category_id.exists' => 'Lütfen geçerli bir sürücü belgesi kategorisi seçiniz.',
        'licence_type_id.exists' => 'Lütfen geçerli bir sürücü belgesi seçiniz.',
        'name.required' => 'Sürücü Belgesi adını yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.licence-type.licence-type-create');
    }

    public function mount(LicenceTypeCategoryService $licenceTypeCategoryService)
    {
        $this->licenceTypeCategories = $licenceTypeCategoryService->all(['id', 'name']);
        $this->licenceTypes = LicenceType::query()
            ->where(['licence_type_category_id' => $this->licence_type_category_id])
            ->with('licence_type')
            ->orderBy('id')
            ->get(['id', 'licence_type_category_id', 'licence_type_id', 'name']);
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(LicenceTypeService $licenceTypeService)
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $licenceType = $licenceTypeService->create([
                'licence_type_category_id' => $this->licence_type_category_id ?? null,
                'licence_type_id' => $this->licence_type_id ?? null,
                'name' => $this->name,
                'status' => $this->status == false ? 0 : 1,
            ]);

            $this->dispatch('pg:eventRefresh-LicenceTypeTable');
            $msg = 'Sürücü Belgesi oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset(['name']);
        } catch (\Exception $exception) {
            $error = "Sürücü Belgesi oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }

    public function updatedLicenceTypeCategoryId()
    {
        $this->licenceTypes = LicenceType::query()->where(['licence_type_category_id' => $this->licence_type_category_id])->with('licence_type')->orderBy('id')->get(['id', 'licence_type_id', 'name']);
    }
}
