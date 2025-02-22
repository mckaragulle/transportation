<?php

namespace App\Livewire\Tenant\Hgs;

use App\Models\Landlord\LandlordHgsTypeCategory;
use App\Models\Tenant\Hgs;
use App\Models\Tenant\HgsType;
use App\Services\HgsService;
use App\Services\HgsTypeCategoryService;
use App\Services\HgsTypeService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class HgsEdit extends Component
{
    use LivewireAlert, WithFileUploads;

    public null|Collection $hgsTypeCategoryDatas;
    public null|Collection $hgses;

    public ?Hgs $hgs = null;

    public null|array $hgs_type_categories = [];
    public null|array $hgs_types = [];
    public null|int $number;
    public $oldfilename;
    public $filename;
    public null|string $buyed_at;
    public null|string $canceled_at;
    public bool $status = true;

    protected HgsTypeCategoryService $hgsTypeCategoryService;
    protected HgsTypeService $hgsTypeService;
    /**
     * List of add/edit form rules
     */
    public function rules()
    {
        return [
            'hgs_type_categories' => ['required', 'array'],
            'hgs_type_categories.*' => ['required'],
            'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
            'number' => ['required'],
            'filename' => ['nullable', 'image', 'max:4096'],
            'buyed_at' => ['required', 'date'],
            'canceled_at' => ['nullable', 'date'],
        ];
    }

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

    public function mount($id = null, LandlordHgsTypeCategory $hgsTypeCategory, HgsService $hgsService)
    {
        if (!is_null($id)) {
            $this->hgs = $hgsService->findById($id);
            $this->status = $this->hgs->status;
            $this->number = $this->hgs->number;
            if (isset($this->hgs?->filename) && Storage::exists($this->hgs?->filename)) {
                $this->oldfilename = $this->hgs->filename;
            }
            $this->buyed_at = $this->hgs->buyed_at ?? null;
            $this->canceled_at = $this->hgs->canceled_at ?? null;
            //hgs_type_categories
            $this->hgs_type_categories = $this->hgs_types = $this->hgs->hgs_types->pluck('id', 'hgs_type_category_id')->toArray();
            $this->hgsTypeCategoryDatas = $hgsTypeCategory->query()
                ->with(['hgs_types:id,hgs_type_category_id,hgs_type_id,name', 'hgs_types.hgs_types:id,hgs_type_category_id,hgs_type_id,name'])
                ->get(['id', 'name']);
        } else {
            return $this->redirect(route('tenant.hgses.list'));
        }
    }

    public function render()
    {
        return view('livewire.tenant.hgs.hgs-edit');
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
            $this->hgs->number = $this->number;

            $filename = null;
            if ($this->filename != null) {
                $filename = $this->filename->store(path: 'public/photos');
                $this->hgs->filename = $filename;
            }

            $this->hgs->buyed_at = $this->buyed_at ?? null;
            $this->hgs->canceled_at = $this->canceled_at ?? null;
            $this->hgs->status = $this->status == false ? 0 : 1;
            $this->hgs->save();
            if (!is_null($this->oldfilename) && Storage::exists($this->oldfilename)) {
                if (!is_null($filename) && Storage::exists($filename)) {
                    Storage::delete($this->oldfilename);
                }
            }

            foreach ($this->hgs_type_categories as $hgs_type_category_id => $hgs_type_id) {
                DB::table('hgs_type_category_hgs_type_hgs')
                    ->where(['hgs_type_category_id' => $hgs_type_category_id, 'hgs_id' => $this->hgs->id])
                    ->delete();
            }
            foreach ($this->hgs_type_categories as $hgs_type_category_id => $hgs_type_id) {
                $data = DB::table('hgs_type_category_hgs_type_hgs')
                    ->where(['hgs_type_category_id' => $hgs_type_category_id, 'hgs_id' => $this->hgs->id])
                    ->first();
                DB::insert('insert into hgs_type_category_hgs_type_hgs (hgs_type_category_id, hgs_type_id, hgs_id) values (?, ?, ?)', [$hgs_type_category_id, $hgs_type_id, $this->hgs->id]);
            }

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
        $this->hgs_types = HgsType::query()
            ->where(['hgs_type_category_id' => $this->hgs_type_category_id])
            ->with('hgs_type')
            ->orderBy('id')
            ->get(['id', 'hgs_type_id', 'name']);
    }
}
