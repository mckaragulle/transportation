<?php

namespace App\Livewire\Licence;

use App\Models\Licence;
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
use Illuminate\Support\Facades\Storage;

class LicenceEdit extends Component
{
    use LivewireAlert, WithFileUploads;

    public null|Collection $licenceTypeCategoryDatas;
    public null|Collection $licences;

    public ?Licence $licence = null;

    public null|array $licence_type_categories = [];
    public null|array $licence_types = [];
    public null|string $number = null;
    public null|string $started_at = null;
    public null|string $finished_at = null;
    public null|string $detail = null;
    public $oldfilename;
    public $filename;
    public bool $status = true;

    protected LicenceTypeCategoryService $licenceTypeCategoryService;
    protected LicenceTypeService $licenceTypeService;
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
        'filename.image' => 'Belge için dosya seçiniz yazınız.',
        'filename.max' => 'Dosya boyutu en fazla 4 mb olmalıdır.',
        'filename.uploaded' => 'Dosya boyutu en fazla 4 mb olmalıdır.',

        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null, LicenceTypeCategory $licenceTypeCategory, LicenceService $licenceService)
    {
        if (!is_null($id)) {
            $this->licence = $licenceService->findById($id);
            $this->status = $this->licence->status;
            $this->number = $this->licence->number;
            $this->started_at = $this->licence->started_at;
            $this->finished_at = $this->licence->finished_at;
            $this->detail = $this->licence->detail;

            if (isset($this->licence?->filename) && Storage::exists($this->licence?->filename)) {
                $this->oldfilename = $this->licence->filename;
            }
            //licence_type_categories
            $this->licence_type_categories = $this->licence_types = $this->licence->licence_types->pluck('id', 'licence_type_category_id')->toArray();
            $this->licenceTypeCategoryDatas = $licenceTypeCategory->query()
                ->with(['licence_types:id,licence_type_category_id,licence_type_id,name', 'licence_types.licence_types:id,licence_type_category_id,licence_type_id,name'])
                ->get(['id', 'name']);
        } else {
            return $this->redirect(route('licences.list'));
        }
    }

    public function render()
    {
        return view('livewire.licence.licence-edit');
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
            $this->licence->number = $this->number;
            $this->licence->started_at = $this->started_at;
            $this->licence->finished_at = $this->finished_at;
            $this->licence->detail = $this->detail;

            $filename = null;
            if ($this->filename != null) {
                $filename = $this->filename->store(path: 'public/photos');
                $this->licence->filename = $filename;
            }
            $this->licence->status = $this->status == false ? 0 : 1;
            $this->licence->save();
            if (!is_null($this->oldfilename) && Storage::exists($this->oldfilename)) {
                if (!is_null($filename) && Storage::exists($filename)) {
                    Storage::delete($this->oldfilename);
                }
            }

            foreach ($this->licence_type_categories as $licence_type_category_id => $licence_type_id) {
                DB::table('licence_type_category_licence_type_licence')
                    ->where(['licence_type_category_id' => $licence_type_category_id, 'licence_id' => $this->licence->id])
                    ->delete();
            }
            foreach ($this->licence_type_categories as $licence_type_category_id => $licence_type_id) {
                $data = DB::table('licence_type_category_licence_type_licence')
                    ->where(['licence_type_category_id' => $licence_type_category_id, 'licence_id' => $this->licence->id])
                    ->first();
                DB::insert('insert into licence_type_category_licence_type_licence (licence_type_category_id, licence_type_id, licence_id) values (?, ?, ?)', [$licence_type_category_id, $licence_type_id, $this->licence->id]);
            }

            $msg = 'Sürücü belgesi güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Sürücü belgesi güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }

    public function updatedLicenceTypeCategoryId()
    {
        $this->licence_types = LicenceType::query()
            ->where(['licence_type_category_id' => $this->licence_type_category_id])
            ->with('licence_type')
            ->orderBy('id')
            ->get(['id', 'licence_type_id', 'name']);
    }
}
