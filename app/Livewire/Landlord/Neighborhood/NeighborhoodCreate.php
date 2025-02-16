<?php

namespace App\Livewire\Landlord\Neighborhood;

use App\Models\Landlord\LandlordDistrict;
use App\Services\Landlord\LandlordCityService;
use App\Services\Landlord\LandlordDistrictService;
use App\Services\Landlord\LandlordNeighborhoodService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class NeighborhoodCreate extends Component
{
    use LivewireAlert;

    public null|Collection $cities;
    public null|Collection $neighborhood;
    public null|Collection $districts;
    public null|string|int $city_id = null;
    public null|string|int $district_id = null;
    public null|string $postcode;
    public null|string $name;

    public bool $status = true;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'city_id' => ['required', 'exists:cities,id'],
        'district_id' => ['required', 'exists:districts,id'],
        'name' => ['required'],
        'postcode' => ['required', 'unique:neighborhoods'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'city_id.required' => 'Lütfen il seçiniz.',
        'city_id.exists' => 'Lütfen geçerli bir il seçiniz.',
        'district_id.required' => 'Lütfen ilçe seçiniz.',
        'district_id.exists' => 'Lütfen geçerli bir ilçe seçiniz.',
        'name.required' => 'Mahalle adını yazınız.',
        'postcode.required' => 'Posta kodunu yazınız.',
        'postcode.unique' => 'Bu posta kodu zaten kullanılmaktadır.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.landlord.neighborhood.neighborhood-create');
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
    public function store(LandlordNeighborhoodService $neighborhoodService)
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $neighborhood = $neighborhoodService->create([
                'city_id' => $this->city_id ?? null,
                'district_id' => $this->district_id ?? null,
                'name' => $this->name,
                'postcode' => $this->postcode,
                'status' => $this->status == false ? 0 : 1,
            ]);

            $this->dispatch('pg:eventRefresh-NeighborhoodTable');
            $msg = 'Mahalle oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset(['name', 'city_id', 'district_id']);
        } catch (\Exception $exception) {
            $error = "Mahalle oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }

    public function updatedCityId(): void
    {
        $this->districts = LandlordDistrict::query()->where(['city_id' => $this->city_id])->orderBy('id')->get(['id', 'city_id', 'name']);
    }
}
