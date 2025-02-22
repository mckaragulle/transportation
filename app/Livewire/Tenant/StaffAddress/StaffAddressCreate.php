<?php

namespace App\Livewire\Tenant\StaffAddress;

use App\Models\Tenant\District;
use App\Models\Tenant\Locality;
use App\Models\Tenant\Neighborhood;
use App\Services\Tenant\StaffAddressService;
use App\Services\Tenant\StaffService;
use App\Services\Tenant\CityService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class StaffAddressCreate extends Component
{
    use LivewireAlert;

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
    public bool $is_show = false;

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

    public function render()
    {
        return view('livewire.tenant.staff-address.staff-address-create');
    }

    public function mount(null|string $id = null, bool $is_show, StaffService $staffService, CityService $cityService)
    {
        $this->is_show = $is_show;
        $this->staff_id = $id;

        $this->cities = $cityService->all(['id', 'name']);
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(StaffAddressService $staffAddressService)
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $staff = $staffAddressService->create([
                'staff_id' => $this->staff_id ?? null,
                'city_id' => $this->city_id ?? null,
                'district_id' => $this->district_id ?? null,
                'neighborhood_id' => $this->neighborhood_id ?? null,
                'locality_id' => $this->locality_id ?? null,
                'name' => $this->name ?? null,
                'address1' => $this->address1 ?? null,
                'address2' => $this->address2 ?? null,
                'phone1' => $this->phone1 ?? null,
                'phone2' => $this->phone2 ?? null,
                'email' => $this->email ?? null,
                'detail' => $this->detail ?? null,
                'status' => $this->status == false ? 0 : 1,
            ]);

            $this->dispatch('pg:eventRefresh-StaffAddressTable');
            $msg = 'Personel Adresi oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset();
        } catch (\Exception $exception) {
            $error = "Personel Adresi oluşturulamadı. {$exception->getMessage()}";
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
