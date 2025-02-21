<?php

namespace App\Livewire\Landlord\Locality;

use App\Models\Landlord\LandlordDistrict;
use App\Models\Landlord\LandlordLocality;
use App\Models\Landlord\LandlordNeighborhood;
use App\Services\Landlord\LandlordCityService;
use App\Services\Landlord\LandlordDistrictService;
use App\Services\Landlord\LandlordLocalityService;
use App\Services\Landlord\LandlordNeighborhoodService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class LocalityEdit extends Component
{
    use LivewireAlert;

    public null|Collection $cities;
    public null|Collection $districts;
    public null|Collection $neighborhoods;

    public ?LandlordLocality $locality = null;

    public null|string $city_id = null;
    public null|string $district_id = null;
    public null|string $neighborhood_id = null;
    public null|string $name;
    public bool $status = true;

    protected LandlordCityService $cityService;
    protected LandlordDistrictService $districtService;
    protected LandlordLocalityService $localityService;

    /**
     * List of add/edit form rules
     */
    public function rules()
    {
        return [
            'city_id' => [
                'required',
                'exists:cities,id',
            ],
            'district_id' => [
                'required',
                'exists:districts,id',
            ],
            'neighborhood_id' => [
                'required',
                'exists:neighborhoods,id',
            ],
            'name' => [
                'required',
                'unique:localities'
            ],
            'status' => [
                'in:true,false,null,0,1,active,passive,',
                'nullable',
            ],
        ];
    }

    protected $messages = [
        'city_id.required' => 'Lütfen il seçiniz.',
        'city_id.exists' => 'Lütfen geçerli bir il seçiniz.',
        'district_id.required' => 'Lütfen ilçe seçiniz.',
        'district_id.exists' => 'Lütfen geçerli bir ilçe seçiniz.',
        'neighborhood_id.required' => 'Lütfen semt seçiniz.',
        'neighborhood_id.exists' => 'Lütfen geçerli bir semt seçiniz.',
        'name.required' => 'Mahalle adını yazınız.',
        'name.unique' => 'Bu mahalle adı zaten kullanılmaktadır.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null, LandlordCityService $cityService, LandlordDistrictService $districtService, LandlordNeighborhoodService $neighborhoodService, LandlordLocalityService $localityService)
    {
        if (!is_null($id)) {
            $this->locality = $localityService->findById($id);

            $this->city_id = $this->locality->city_id;
            $this->district_id = $this->locality->district_id;
            $this->neighborhood_id = $this->locality->neighborhood_id;
            $this->name = $this->locality->name ?? null;
            $this->status = $this->locality->status;
            $this->cities = $cityService->all(['id', 'name']);
            $this->districts = $districtService->where(['city_id' => $this->city_id])->get(['id', 'city_id', 'name']);
            $this->neighborhoods = $neighborhoodService->where(['district_id' => $this->district_id])->get(['id', 'district_id', 'name']);
        } else {
            return $this->redirect(route('localities.list'));
        }
    }

    public function render()
    {
        return view('livewire.landlord.locality.locality-edit');
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
            $this->locality->city_id = $this->city_id;
            $this->locality->district_id = $this->district_id;
            $this->locality->neighborhood_id = $this->neighborhood_id;
            $this->locality->name = $this->name;
            $this->locality->status = $this->status == false ? 0 : 1;
            $this->locality->save();

            $msg = 'Semt güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Semt güncellenemedi. {$exception->getMessage()}";
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
