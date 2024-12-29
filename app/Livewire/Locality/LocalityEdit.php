<?php

namespace App\Livewire\Locality;

use App\Models\District;
use App\Models\Locality;
use App\Models\Neighborhood;
use App\Services\CityService;
use App\Services\DistrictService;
use App\Services\LocalityService;
use App\Services\NeighborhoodService;
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

    public ?Locality $locality = null;

    public null|string $city_id = null;
    public null|string $district_id = null;
    public null|string $neighborhood_id = null;
    public null|string $name;
    public bool $status = true;

    protected CityService $cityService;
    protected DistrictService $districtService;
    protected LocalityService $localityService;
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

    public function mount($id = null, CityService $cityService, DistrictService $districtService, NeighborhoodService $neighborhoodService, LocalityService $localityService)
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
        return view('livewire.locality.locality-edit');
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

    public function updatedCityId()
    {
        $this->districts = District::query()->where(['city_id' => $this->city_id])->orderBy('id')->get(['id', 'city_id', 'name']);
        $this->neighborhoods = null;
    }

    public function updatedDistrictId()
    {
        $this->neighborhoods = Neighborhood::query()->where(['district_id' => $this->district_id])->orderBy('id')->get(['id', 'city_id', 'district_id', 'name']);
    }
}
