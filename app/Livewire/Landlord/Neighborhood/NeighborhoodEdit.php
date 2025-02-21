<?php

namespace App\Livewire\Landlord\Neighborhood;

use App\Models\Landlord\LandlordDistrict;
use App\Models\Landlord\LandlordNeighborhood;
use App\Services\Landlord\LandlordCityService;
use App\Services\Landlord\LandlordDistrictService;
use App\Services\Landlord\LandlordNeighborhoodService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class NeighborhoodEdit extends Component
{
    use LivewireAlert;

    public null|Collection $cities;
    public null|Collection $districts;
    public null|Collection $neighborhoods;

    public ?LandlordNeighborhood $neighborhood = null;

    public null|string $city_id = null;
    public null|string $district_id = null;
    public null|string $name;
    public null|string $postcode;
    public bool $status = true;

    protected LandlordCityService $cityService;
    protected LandlordDistrictService $districtService;
    protected LandlordNeighborhoodService $neighborhoodService;

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
            'name' => [
                'required',
                'unique:neighborhoods'
            ],
            'postcode' => [
                'required',
                'unique:neighborhoods'
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
        'name.required' => 'Mahalle adını yazınız.',
        'name.unique' => 'Bu mahalle adı zaten kullanılmaktadır.',
        'postcode.required' => 'Posta kodunu yazınız.',
        'postcode.unique' => 'Bu posta kodu zaten kullanılmaktadır.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null, LandlordCityService $cityService, LandlordDistrictService $districtService, LandlordNeighborhoodService $neighborhoodService)
    {
        if (!is_null($id)) {
            $this->neighborhood = $neighborhoodService->findById($id);

            $this->city_id = $this->neighborhood->city_id;
            $this->district_id = $this->neighborhood->district_id;
            $this->name = $this->neighborhood->name ?? null;
            $this->postcode = $this->neighborhood->postcode ?? null;
            $this->status = $this->neighborhood->status;
            $this->cities = $cityService->all(['id', 'name']);
            $this->districts = $districtService->where(['city_id' => $this->city_id])->get(['id', 'city_id', 'name']);
        } else {
            return $this->redirect(route('neighborhoods.list'));
        }
    }

    public function render()
    {
        return view('livewire.landlord.neighborhood.neighborhood-edit');
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
            $this->neighborhood->city_id = $this->city_id;
            $this->neighborhood->district_id = $this->district_id;
            $this->neighborhood->name = $this->name;
            $this->neighborhood->postcode = $this->postcode;
            $this->neighborhood->status = $this->status == false ? 0 : 1;
            $this->neighborhood->save();

            $msg = 'Mahalle güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Mahalle güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }

    public function updatedCityId()
    {
        $this->districts = LandlordDistrict::query()->where(['city_id' => $this->city_id])->orderBy('id')->get(['id', 'city_id', 'name']);
    }
}
