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

class LicenceTypeEdit extends Component
{
    use LivewireAlert;

    public null|Collection $licenceTypeCategories;
    public null|Collection $licenceTypes;

    public ?LicenceType $licenceType = null;

    public null|string $licence_type_category_id = null;
    public null|string $licence_type_id = null;
    public null|string $name;
    public bool $status = true;

    protected LicenceTypeCategoryService $licenceTypeCategoryService;
    protected LicenceTypeService $licenceTypeService;
    /**
     * List of add/edit form rules
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
            ],
            'licence_type_category_id' => [
                'required',
                'exists:licence_type_categories,id',
            ],
            'licence_type_id' => [
                'nullable',
                'exists:licence_types,id',
            ],
            'status' => [
                'in:true,false,null,0,1,active,passive,',
                'nullable',
            ],
        ];
    }

    protected $messages = [
        'licence_type_category_id.required' => 'Lütfen sürücü belgesi kategorisini seçiniz.',
        'licence_type_category_id.exists' => 'Lütfen geçerli bir sürücü belgesi kategorisi seçiniz.',
        'licence_type_id.exists' => 'Lütfen geçerli bir sürücü belgesi seçiniz.',
        'name.required' => 'Sürücü Belgesi adını yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null, LicenceTypeCategoryService $licenceTypeCategoryService, LicenceTypeService $licenceTypeService)
    {
        if (!is_null($id)) {
            $this->licenceType = $licenceTypeService->findById($id);
            $this->licence_type_category_id = $this->licenceType->licence_type_category_id;
            $this->licence_type_id = $this->licenceType->licence_type_id??null;
            $this->name = $this->licenceType->name??null;
            $this->status = $this->licenceType->status;
            $this->licenceTypeCategories = $licenceTypeCategoryService->all();
            $this->licenceTypes = LicenceType::query()->where(['licence_type_category_id' => $this->licence_type_category_id])->with('licence_type')->orderBy('id')->get(['id', 'licence_type_id', 'name']);

        } else {
            return $this->redirect(route('licenceTypes.list'));
        }
    }

    public function render()
    {
        return view('livewire.licence-type.licence-type-edit');
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
            $this->licenceType->licence_type_category_id = $this->licence_type_category_id;
            $this->licenceType->licence_type_id = $this->licence_type_id ?? null;
            $this->licenceType->name = $this->name;
            $this->licenceType->status = $this->status == false ? 0 : 1;
            $this->licenceType->save();

            $msg = 'Sürücü Belgesi güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Sürücü Belgesi güncellenemedi. {$exception->getMessage()}";
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
