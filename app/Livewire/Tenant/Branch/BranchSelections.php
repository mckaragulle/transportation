<?php

namespace App\Livewire\Tenant\Branch;

use App\Models\Tenant\Dealer;
use App\Models\Tenant\DealerSelection;
use App\Services\DealerAddressService;
use App\Services\DealerOfficerService;
use App\Services\Tenant\DealerService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class BranchSelections extends Component
{
    use LivewireAlert;

    public null|DealerSelection $dealerSelection = null;
    public null|Collection $addresses;
    public null|Collection $officers;

    public null|Dealer $dealer = null;

    public string $dealer_id;
    public null|string $dealer_address_id = null;
    public null|string $dealer_officer_id = null;

    public function mount(null|string $id = null, DealerService $dealerService, DealerAddressService $dealerAddressService, DealerOfficerService $dealerOfficerService)
    {
        $this->dealer_id = $id;
        $this->dealer = $dealerService->findById($id);
        $dealer_array = ['dealer_id' => $id];
        $this->addresses = $dealerAddressService->where($dealer_array)->get(['id', 'dealer_id', 'name']);
        $this->officers = $dealerOfficerService->where($dealer_array)->get(['id', 'dealer_id', 'name', 'surname', 'number', 'title']);

        $dealerSelection = DealerSelection::query()->where($dealer_array);

        if($dealerSelection->exists()){
            $this->dealerSelection = $dealerSelection->first();
            $this->dealer_address_id = $this->dealerSelection->dealer_address_id ?? null;
            $this->dealer_officer_id = $this->dealerSelection->dealer_officer_id ?? null;
        }
    }

    public function render()
    {
        return view('livewire.tenant.branch.branch-selections');
    }

    public function store()
    {
        DB::beginTransaction();
        try {
            DealerSelection::updateOrCreate(
    [
                    'dealer_id' => $this->dealer_id
                ],
        [
                    'dealer_address_id' => $this->dealer_address_id ?? null,
                    'dealer_officer_id' => $this->dealer_officer_id ?? null
                ]
            );

            $msg = 'Bayi seçenekleri güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Bayi seçenekleri güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
