<?php

namespace App\Livewire\Landlord\DealerAddress;

use App\Models\District;
use App\Models\Tenant\DealerAddress;
use App\Services\CityService;
use App\Services\DealerAddressService;
use App\Services\DistrictService;
use App\Services\LocalityService;
use App\Services\NeighborhoodService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class DealerAddressEdit extends Component
{
    use LivewireAlert;

    public ?DealerAddress $dealerAddress = null;
    public null|Collection $cities = null;
    public null|Collection $districts = null;
    public null|Collection $neighborhoods = null;
    public null|Collection $localities = null;
    public null|string $city_id = null;
    public null|string $district_id = null;
    public null|string $neighborhood_id = null;
    public null|string $locality_id = null;
    public null|string $name = null;
    public null|string $address1 = null;
    public null|string $address2 = null;
    public null|string $phone1 = null;
    public null|string $phone2 = null;
    public null|string $email = null;
    public null|string $detail = null;

    public bool $status = true;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'city_id' => ['required', 'exists:cities,id'],
        'district_id' => ['required', 'exists:districts,id'],
        'neighborhood_id' => ['required', 'exists:neighborhoods,id'],
        'locality_id' => ['required', 'exists:localities,id'],
        'address1' => ['required'],
        'address2' => ['nullable'],
        'phone1' => ['nullable'],
        'phone2' => ['nullable'],
        'email' => ['nullable'],
        'detail' => ['nullable'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'city_id.required' => 'Lütfen şehir seçiniz yazınız.',
        'city_id.exists' => 'Lütfen geçerli bir şehir seçiniz yazınız.',
        'district_id.required' => 'Lütfen ilçe seçiniz yazınız.',
        'district_id.exists' => 'Lütfen geçerli bir ilçe seçiniz yazınız.',
        'neighborhood_id.required' => 'Lütfen mahalle seçiniz yazınız.',
        'neighborhood_id.exists' => 'Lütfen geçerli bir mahalle seçiniz yazınız.',
        'locality_id.required' => 'Lütfen semt seçiniz yazınız.',
        'locality_id.exists' => 'Lütfen geçerli bir semt seçiniz yazınız.',
        'name.required' => 'Lütfen adres başlığını yazınız.',
        'address1.required' => 'Lütfen 1. adresi yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null, CityService $cityService, DistrictService $districtService, NeighborhoodService $neighborhoodService, LocalityService $localityService, DealerAddressService $dealerAddressService)
    {
        //TODO: Burada bayi ve cari seçimi yapılacak.
        if (!is_null($id)) {
            $this->dealerAddress = $dealerAddressService->findById($id);
            $this->cities = $cityService->orderBy('plate', 'asc')->get(['id', 'name']);
            $this->districts = $districtService->get(['id', 'name']);
            $this->neighborhoods = $neighborhoodService->get(['id', 'name']);
            $this->localities = $localityService->get(['id', 'name']);
            $this->city_id = $this->dealerAddress->city_id;
            $this->district_id = $this->dealerAddress->district_id;
            $this->neighborhood_id = $this->dealerAddress->neighborhood_id;
            $this->locality_id = $this->dealerAddress->locality_id;
            $this->name = $this->dealerAddress->name;
            $this->address1 = $this->dealerAddress->address1;
            $this->address2 = $this->dealerAddress->address2;
            $this->phone1 = $this->dealerAddress->phone1;
            $this->phone2 = $this->dealerAddress->phone2;
            $this->email = $this->dealerAddress->email;
            $this->detail = $this->dealerAddress->detail;
            $this->status = $this->dealerAddress->status;
        } else {
            return $this->redirect(route('dealer_addresses.list'));
        }
    }

    public function render()
    {
        return view('livewire.landlord.dealer-address.dealer-address-edit');
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
            $this->dealerAddress->city_id = $this->city_id;
            $this->dealerAddress->district_id = $this->district_id;
            $this->dealerAddress->neighborhood_id = $this->neighborhood_id;
            $this->dealerAddress->locality_id = $this->locality_id;
            $this->dealerAddress->name = $this->name;
            $this->dealerAddress->address1 = $this->address1;
            $this->dealerAddress->address2 = $this->address2 ?? null;
            $this->dealerAddress->phone1 = $this->phone1 ?? null;
            $this->dealerAddress->phone2 = $this->phone2 ?? null;
            $this->dealerAddress->email = $this->email ?? null;
            $this->dealerAddress->detail = $this->detail ?? null;

            $this->dealerAddress->status = $this->status == false ? 0 : 1;
            $this->dealerAddress->save();

            $msg = 'Bayi adresi güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Bayi adresi güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }

    public function updatedCityId(DistrictService $districtService)
    {
        $this->districts = $districtService->where(['city_id' => $this->city_id])->orderBy('name', 'asc')->get(['id', 'city_id', 'name']);
        $this->neighborhoods = null;
        $this->localities = null;
    }

    public function updatedDistrictId(NeighborhoodService $neighborhoodService)
    {
        $this->neighborhoods = $neighborhoodService->where(['district_id' => $this->district_id])->orderBy('name', 'asc')->get(['id', 'city_id', 'district_id', 'name']);
        $this->localities = null;
    }

    public function updatedNeighborhoodId(LocalityService $localityService)
    {
        $this->localities = $localityService->where(['neighborhood_id' => $this->neighborhood_id])->orderBy('name', 'asc')->get(['id', 'city_id', 'district_id', 'neighborhood_id', 'name']);
    }
}
