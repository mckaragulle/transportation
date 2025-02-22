<?php

namespace App\Livewire\Tenant\StaffAddress;

use App\Models\Tenant\District;
use App\Models\Tenant\Locality;
use App\Models\Tenant\Neighborhood;
use App\Services\Tenant\StaffAddressService;
use App\Services\Tenant\CityService;
use App\Services\Tenant\DistrictService;
use App\Services\Tenant\LocalityService;
use App\Services\Tenant\NeighborhoodService;
use App\Services\Tenant\StaffService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class StaffAddressEdit extends Component
{
    use LivewireAlert;

    public null|Model $staffAddress = null;
    public null|Collection $staffs = null;
    public null|Collection $cities = null;
    public null|Collection $districts = null;
    public null|Collection $neighborhoods = null;
    public null|Collection $localities = null;
    public null|string $staff_id = null;
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
        'staff_id' => ['required', 'exists:staffs,id'],
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
        'staff_id.required' => 'Lütfen personel seçiniz yazınız.',
        'staff_id.exists' => 'Lütfen geçerli bir personel seçiniz yazınız.',
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

    public function mount($id = null, StaffService $staffService, CityService $cityService, DistrictService $districtService, NeighborhoodService $neighborhoodService, LocalityService $localityService, StaffAddressService $staffAddressService)
    {
        if (!is_null($id)) {
            $this->staffAddress = $staffAddressService->findById($id);
            $this->staffs = $staffService->all(['id', 'name']);
            $this->cities = $cityService->all(['id', 'name']);
            $this->districts = $districtService->all(['id', 'name']);
            $this->neighborhoods = $neighborhoodService->all(['id', 'name']);
            $this->localities = $localityService->all(['id', 'name']);
            $this->staff_id = $this->staffAddress->staff_id;
            $this->city_id = $this->staffAddress->city_id;
            $this->district_id = $this->staffAddress->district_id;
            $this->neighborhood_id = $this->staffAddress->neighborhood_id;
            $this->locality_id = $this->staffAddress->locality_id;
            $this->name = $this->staffAddress->name;
            $this->address1 = $this->staffAddress->address1;
            $this->address2 = $this->staffAddress->address2;
            $this->phone1 = $this->staffAddress->phone1;
            $this->phone2 = $this->staffAddress->phone2;
            $this->email = $this->staffAddress->email;
            $this->detail = $this->staffAddress->detail;
            $this->status = $this->staffAddress->status;
        } else {
            return $this->redirect(route('staff_addresses.list'));
        }
    }

    public function render()
    {
        return view('livewire.tenant.staff-address.staff-address-edit');
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
            $this->staffAddress->staff_id = $this->staff_id;
            $this->staffAddress->city_id = $this->city_id;
            $this->staffAddress->district_id = $this->district_id;
            $this->staffAddress->neighborhood_id = $this->neighborhood_id;
            $this->staffAddress->locality_id = $this->locality_id;
            $this->staffAddress->name = $this->name;
            $this->staffAddress->address1 = $this->address1;
            $this->staffAddress->address2 = $this->address2 ?? null;
            $this->staffAddress->phone1 = $this->phone1 ?? null;
            $this->staffAddress->phone2 = $this->phone2 ?? null;
            $this->staffAddress->email = $this->email ?? null;
            $this->staffAddress->detail = $this->detail ?? null;

            $this->staffAddress->status = $this->status == false ? 0 : 1;
            $this->staffAddress->save();

            $msg = 'Personel adresi güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Personel adresi güncellenemedi. {$exception->getMessage()}";
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
