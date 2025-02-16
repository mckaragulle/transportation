<?php

namespace App\Livewire\AccountAddress;

use App\Models\AccountAddress;
use App\Models\District;
use App\Models\Locality;
use App\Models\Neighborhood;
use App\Services\AccountAddressService;
use App\Services\CityService;
use App\Services\DealerService;
use App\Services\DistrictService;
use App\Services\LocalityService;
use App\Services\NeighborhoodService;
use App\Services\Tenant\AccountService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class AccountAddressEdit extends Component
{
    use LivewireAlert;

    public ?AccountAddress $accountAddress = null;
    public null|Collection $accounts = null;
    public null|Collection $cities = null;
    public null|Collection $districts = null;
    public null|Collection $neighborhoods = null;
    public null|Collection $localities = null;
    public null|string $dealer_id = null;
    public null|string $account_id = null;
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
        'dealer_id' => ['required', 'exists:dealers,id'],
        'account_id' => ['required', 'exists:accounts,id'],
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
        'account_id.required' => 'Lütfen cari seçiniz yazınız.',
        'account_id.exists' => 'Lütfen geçerli bir cari seçiniz yazınız.',
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

    public function mount($id = null, DealerService $dealerService, AccountService $accountService, CityService $cityService, DistrictService $districtService, NeighborhoodService $neighborhoodService, LocalityService $localityService, AccountAddressService $accountAddressService)
    {
        //TODO: Burada bayi ve cari seçimi yapılacak.
        if (!is_null($id)) {
            $this->accountAddress = $accountAddressService->findById($id);
            $this->accounts = $accountService->all(['id', 'name']);
            $this->cities = $cityService->all(['id', 'name']);
            $this->districts = $districtService->all(['id', 'name']);
            $this->neighborhoods = $neighborhoodService->all(['id', 'name']);
            $this->localities = $localityService->all(['id', 'name']);
            $this->dealer_id = $this->accountAddress->dealer_id;
            $this->account_id = $this->accountAddress->account_id;
            $this->city_id = $this->accountAddress->city_id;
            $this->district_id = $this->accountAddress->district_id;
            $this->neighborhood_id = $this->accountAddress->neighborhood_id;
            $this->locality_id = $this->accountAddress->locality_id;
            $this->name = $this->accountAddress->name;
            $this->address1 = $this->accountAddress->address1;
            $this->address2 = $this->accountAddress->address2;
            $this->phone1 = $this->accountAddress->phone1;
            $this->phone2 = $this->accountAddress->phone2;
            $this->email = $this->accountAddress->email;
            $this->detail = $this->accountAddress->detail;
            $this->status = $this->accountAddress->status;
        } else {
            return $this->redirect(route('account_addresses.list'));
        }
    }

    public function render()
    {
        return view('livewire.account-address.account-address-edit');
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
            $this->accountAddress->dealer_id = $this->dealer_id;
            $this->accountAddress->account_id = $this->account_id;
            $this->accountAddress->city_id = $this->city_id;
            $this->accountAddress->district_id = $this->district_id;
            $this->accountAddress->neighborhood_id = $this->neighborhood_id;
            $this->accountAddress->locality_id = $this->locality_id;
            $this->accountAddress->name = $this->name;
            $this->accountAddress->address1 = $this->address1;
            $this->accountAddress->address2 = $this->address2 ?? null;
            $this->accountAddress->phone1 = $this->phone1 ?? null;
            $this->accountAddress->phone2 = $this->phone2 ?? null;
            $this->accountAddress->email = $this->email ?? null;
            $this->accountAddress->detail = $this->detail ?? null;

            $this->accountAddress->status = $this->status == false ? 0 : 1;
            $this->accountAddress->save();

            $msg = 'Cari adresi güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Cari adresi güncellenemedi. {$exception->getMessage()}";
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
