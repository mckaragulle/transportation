<?php

namespace App\Livewire\Tenant\LicenceTypeCategory;

use App\Models\Tenant\LicenceTypeCategory;
use App\Services\LicenceTypeCategoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class LicenceTypeCategoryEdit extends Component
{
    use LivewireAlert;

    public null|LicenceTypeCategory $licenceTypeCategory;

    public null|string $name;

    public bool $status = true;

    protected LicenceTypeCategoryService $licenceTypeCategoryService;
    /**
     * List of add/edit form rules
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
            ],
            'status' => [
                'in:true,false,null,0,1,active,passive,',
                'nullable',
            ],
        ];
    }

    protected $messages = [
        'name.required' => 'Sürücü belgesi kategorisini yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null, LicenceTypeCategoryService $licenceTypeCategoryService)
    {
        if (!is_null($id)) {

            $this->licenceTypeCategory = $licenceTypeCategoryService->findById($id);
            $this->name = $this->licenceTypeCategory->name;
            $this->status = $this->licenceTypeCategory->status;
        } else {
            return $this->redirect(route('licence_type_categories.list'));
        }
    }

    public function render()
    {
        return view('livewire.licence-type-category.licence-type-category-edit');
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
            $this->licenceTypeCategory->name = $this->name;
            $this->licenceTypeCategory->status = ($this->status == false ? 0 : 1);
            $this->licenceTypeCategory->save();

            $msg = 'Sürücü belgesi kategorisi güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Sürücü belgesi kategorisi güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
