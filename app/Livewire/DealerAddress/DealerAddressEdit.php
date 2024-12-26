<?php

namespace App\Livewire\DealerAddress;

use App\Models\DealerAddress;
use App\Models\District;
use App\Models\Locality;
use App\Models\Neighborhood;
use App\Services\DealerAddressService;
use App\Services\DealerService;
use App\Services\CityService;
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
    public null|int $dealer_id = null;
    public null|int $city_id = null;
    public null|int $district_id = null;
    public null|int $neighborhood_id = null;
    public null|int $locality_id = null;
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
        'dealer_id' => ['required', 'exists:dealers,id'],
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
        'dealer_id.required' => 'Lütfen bir bayi seçiniz.',
        'dealer_id.exists' => 'Lütfen geçerli bir bayi seçiniz.',
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
            $this->cities = $cityService->all(['id', 'name']);
            $this->districts = $districtService->all(['id', 'name']);
            $this->neighborhoods = $neighborhoodService->all(['id', 'name']);
            $this->localities = $localityService->all(['id', 'name']);
            $this->dealer_id = $this->dealerAddress->dealer_id;
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
        return view('livewire.dealer-address.dealer-address-edit');
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
            $this->dealerAddress->dealer_id = $this->dealer_id;
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

    public function updatedCityId()
    {
        $this->districts = District::query()->where(['city_id' => $this->city_id])->orderBy('id')->get(['id', 'city_id', 'name']);
        $this->neighborhoods = null;
    }

    public function updatedDistrictId()
    {
        $this->neighborhoods = Neighborhood::query()->where(['district_id' => $this->district_id])->orderBy('id')->get(['id', 'city_id', 'district_id', 'name']);
    }

    public function updatedNeighborhoodId()
    {
        $this->localities = Locality::query()->where(['neighborhood_id' => $this->neighborhood_id])->orderBy('id')->get(['id', 'city_id', 'district_id', 'neighborhood_id', 'name']);
    }
}
