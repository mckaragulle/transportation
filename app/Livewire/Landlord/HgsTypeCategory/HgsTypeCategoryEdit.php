<?php

namespace App\Livewire\Landlord\HgsTypeCategory;

use App\Models\Landlord\LandlordHgsTypeCategory;
use App\Services\HgsTypeCategoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class HgsTypeCategoryEdit extends Component
{
    use LivewireAlert;

    public null|LandlordHgsTypeCategory $hgsTypeCategory;

    public null|string $name;

    public bool $status = true;

    protected HgsTypeCategoryService $hgsTypeCategoryService;
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
        'name.required' => 'Cari kategorisi yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null, HgsTypeCategoryService $hgsTypeCategoryService)
    {
        if (!is_null($id)) {

            $this->hgsTypeCategory = $hgsTypeCategoryService->findById($id);
            $this->name = $this->hgsTypeCategory->name;
            $this->status = $this->hgsTypeCategory->status;
        } else {
            return $this->redirect(route('hgs_type_categories.list'));
        }
    }

    public function render()
    {
        return view('livewire.landlord.hgs-type-category.hgs-type-category-edit');
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
            $this->hgsTypeCategory->name = $this->name;
            $this->hgsTypeCategory->status = ($this->status == false ? 0 : 1);
            $this->hgsTypeCategory->save();

            $msg = 'Hgs kategorisi güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Hgs kategorisi güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
