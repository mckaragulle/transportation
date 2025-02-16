<?php

namespace App\Livewire\Landlord\Sector;

use App\Models\Landlord\LandlordSector;
use App\Services\Landlord\LandlordSectorService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class SectorEdit extends Component
{
    use LivewireAlert;

    public null|Collection $sectors;

    public ?Model $sector = null;

    public null|int $sector_id = null;
    public null|string $name;
    public bool $status = true;

    protected LandlordSectorService $sectorService;
    /**
     * List of add/edit form rules
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
            ],
            'sector_id' => [
                'nullable',
                'exists:sectors,id',
            ],
            'status' => [
                'in:true,false,null,0,1,active,passive,',
                'nullable',
            ],
        ];
    }

    protected $messages = [
        'sector_id.exists' => 'Lütfen geçerli bir cari sektörü seçiniz.',
        'name.required' => 'Cari sektörü adını yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null, LandlordSectorService $sectorService)
    {
        if (!is_null($id)) {
            $this->sector = $sectorService->findById($id);
            $this->sector_id = $this->sector->sector_id??null;
            $this->name = $this->sector->name??null;
            $this->status = $this->sector->status;

            $this->sectors = LandlordSector::query()
            ->whereNull('sector_id')
            ->orderBy('id')
            ->get(['id', 'sector_id', 'name']);

        } else {
            return $this->redirect(route('sectors.list'));
        }
    }

    public function render()
    {
        return view('livewire.landlord.sector.sector-edit');
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
            $this->sector->sector_id = $this->sector_id ?? null;
            $this->sector->name = $this->name;
            $this->sector->status = $this->status == false ? 0 : 1;
            $this->sector->save();

            $msg = 'Cari sektörü güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Cari sektörü güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
