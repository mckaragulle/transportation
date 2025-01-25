<?php

namespace App\Livewire\Sector;

use App\Models\Sector;
use App\Services\SectorService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class SectorCreate extends Component
{
    use LivewireAlert;
    public null|Collection $sectors;
    public null|string|int $sector_id = null;
    public null|string $name;

    public bool $status = true;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'sector_id' => ['nullable', 'exists:sectors,id'],
        'name' => ['required'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'sector_id.exists' => 'Lütfen geçerli bir sektör seçiniz.',
        'name.required' => 'Cari sektörü adını yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.sector.sector-create');
    }

    public function mount()
    {
        $this->sectors = Sector::query()
            ->whereNull('sector_id')
            ->orderBy('id')
            ->get(['id', 'sector_id', 'name']);
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(SectorService $sectorService)
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $sector = $sectorService->create([
                'sector_id' => $this->sector_id ?? null,
                'name' => $this->name,
                'status' => $this->status == false ? 0 : 1,
            ]);

            $this->dispatch('pg:eventRefresh-SectorTable');
            $msg = 'Cari sektörü oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            
            $this->sectors = Sector::query()
            ->whereNull('sector_id')
            ->orderBy('id')
            ->get(['id', 'sector_id', 'name']);
            
            DB::commit();
            $this->reset(['name', 'sector_id']);
            
        } catch (\Exception $exception) {
            $error = "Cari sektörü oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
