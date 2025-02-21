<?php

namespace App\Livewire\Landlord\Dealer;

use App\Models\Landlord\LandlordDealer;
use App\Models\Landlord\LandlordDealerSelection;
use App\Services\Landlord\LandlordDealerAddressService;
use App\Services\Landlord\LandlordDealerOfficerService;
use App\Services\Landlord\LandlordDealerService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class DealerSelections extends Component
{
    use LivewireAlert;

    public null|LandlordDealerSelection|Model $dealerSelection = null;
    public null|Collection $addresses;
    public null|Collection $officers;

    public null|LandlordDealer $dealer = null;

    public string $dealer_id;
    public null|string $dealer_address_id = null;
    public null|string $dealer_officer_id = null;

    public function mount(null|string $id = null, LandlordDealerService $dealerService, LandlordDealerAddressService $dealerAddressService, LandlordDealerOfficerService $dealerOfficerService)
    {
        $this->dealer_id = $id;
        $this->dealer = $dealerService->findById($id);
        $dealer_array = ['dealer_id' => $id];
        $this->addresses = $dealerAddressService->where($dealer_array)->get(['id', 'dealer_id', 'name']);
        $this->officers = $dealerOfficerService->where($dealer_array)->get(['id', 'dealer_id', 'name', 'surname', 'number', 'title']);

        $dealerSelection = LandlordDealerSelection::query()->where($dealer_array);

        if($dealerSelection->exists()){
            $this->dealerSelection = $dealerSelection->first();
            $this->dealer_address_id = $this->dealerSelection->dealer_address_id ?? null;
            $this->dealer_officer_id = $this->dealerSelection->dealer_officer_id ?? null;
        }
    }

    public function render()
    {
        return view('livewire.landlord.dealer.dealer-selections');
    }

    public function store()
    {
        DB::beginTransaction();
        try {
            LandlordDealerSelection::updateOrCreate(
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
