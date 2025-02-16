<?php

namespace App\Livewire\Landlord\District;

use App\Models\Landlord\LandlordDistrict;
use App\Services\Landlord\LandlordCityService;
use App\Services\Landlord\LandlordDistrictService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class DistrictEdit extends Component
{
    use LivewireAlert;

    public null|Collection $cities;
    public null|Collection $districts;

    public ?LandlordDistrict $district = null;

    public null|string $city_id = null;
    public null|string $name;
    public bool $status = true;

    protected LandlordCityService $cityService;
    protected LandlordDistrictService $districtService;
    /**
     * List of add/edit form rules
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
            ],
            'city_id' => [
                'required',
                'exists:cities,id',
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
        'name.required' => 'İlçe adını yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null, LandlordCityService $cityService, LandlordDistrictService $districtService)
    {
        if (!is_null($id)) {
            $this->district = $districtService->findById($id);
            $this->city_id = $this->district->city_id;
            $this->name = $this->district->name ?? null;
            $this->status = $this->district->status;
            $this->cities = $cityService->all(['id', 'name']);
        } else {
            return $this->redirect(route('districts.list'));
        }
    }

    public function render()
    {
        return view('livewire.landlord.district.district-edit');
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
            $this->district->city_id = $this->city_id;
            $this->district->name = $this->name;
            $this->district->status = $this->status == false ? 0 : 1;
            $this->district->save();

            $msg = 'İlçe güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "İlçe güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }

    public function updatedCityId()
    {
        $this->districts = LandlordDistrict::query()->where(['city_id' => $this->city_id])->orderBy('id')->get(['id', 'name']);
    }
}
