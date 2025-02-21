<?php

namespace App\Livewire\Landlord\Locality;

use App\Models\Landlord\LandlordDistrict;
use App\Models\Landlord\LandlordNeighborhood;
use App\Services\Landlord\LandlordCityService;
use App\Services\Landlord\LandlordDistrictService;
use App\Services\Landlord\LandlordLocalityService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class LocalityCreate extends Component
{
    use LivewireAlert;

    public null|Collection $cities;
    public null|Collection $districts;
    public null|Collection $neighborhoods;
    public null|Collection $locality;
    public null|string|int $city_id = null;
    public null|string|int $district_id = null;
    public null|string|int $neighborhood_id = null;
    public null|string $name;

    public bool $status = true;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'city_id' => ['required', 'exists:cities,id'],
        'district_id' => ['required', 'exists:districts,id'],
        'neighborhood_id' => ['required', 'exists:neighborhoods,id'],
        'name' => ['required'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'city_id.required' => 'Lütfen il seçiniz.',
        'city_id.exists' => 'Lütfen geçerli bir il seçiniz.',
        'district_id.required' => 'Lütfen ilçe seçiniz.',
        'district_id.exists' => 'Lütfen geçerli bir ilçe seçiniz.',
        'neighborhood_id.required' => 'Lütfen semt seçiniz.',
        'neighborhood_id.exists' => 'Lütfen geçerli bir semt seçiniz.',
        'name.required' => 'Mahalle adını yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.landlord.locality.locality-create');
    }

    public function mount(LandlordCityService $cityService, LandlordDistrictService $districtService)
    {
        $this->cities = $cityService->all(['id', 'name']);
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(LandlordLocalityService $localityService)
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $neighborhood = $localityService->create([
                'city_id' => $this->city_id ?? null,
                'district_id' => $this->district_id ?? null,
                'neighborhood_id' => $this->neighborhood_id ?? null,
                'name' => $this->name,
                'status' => $this->status == false ? 0 : 1,
            ]);

            $this->dispatch('pg:eventRefresh-LocalityTable');
            $msg = 'Semt oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset(['name', 'city_id', 'district_id', 'neighborhood_id']);
        } catch (\Exception $exception) {
            $error = "Semt oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }

    public function updatedCityId(): void
    {
        $this->districts = LandlordDistrict::query()->where(['city_id' => $this->city_id])->orderBy('id')->get(['id', 'city_id', 'name']);
        $this->neighborhoods = null;
    }

    public function updatedDistrictId(): void
    {
        $this->neighborhoods = LandlordNeighborhood::query()->where(['district_id' => $this->district_id])->orderBy('id')->get(['id', 'city_id', 'district_id', 'name']);
    }
}
