<?php

namespace App\Livewire\District;

use App\Models\District;
use App\Models\City;
use App\Services\CityService;
use App\Services\DistrictService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class DistrictCreate extends Component
{
    use LivewireAlert;

    public null|Collection $cities;
    public null|Collection $districts;
    public null|string|int $city_id = null;
    public null|string $name;

    public bool $status = true;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'city_id' => ['required', 'exists:cities,id'],
        'name' => ['required'],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'city_id.required' => 'Lütfen il seçiniz.',
        'city_id.exists' => 'Lütfen geçerli bir il seçiniz.',
        'name.required' => 'İlçe adını yazınız.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.district.district-create');
    }

    public function mount(CityService $cityService)
    {
        $this->cities = $cityService->all(['id', 'name']);
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(DistrictService $districtService)
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $district = $districtService->create([
                'city_id' => $this->city_id ?? null,
                'name' => $this->name,
                'status' => $this->status == false ? 0 : 1,
            ]);

            $this->dispatch('pg:eventRefresh-DistrictTable');
            $msg = 'İlçe oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            $this->districts = District::query()
                ->where(['city_id' => $this->city_id])
                // ->whereDoesntHave('citys')
                ->with('city')
                ->orderBy('id')
                ->get(['id', 'city_id', 'name']);
            DB::commit();
            $this->reset(['name', 'city_id']);
            
        } catch (\Exception $exception) {
            $error = "İlçe oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }

    public function updatedCityId()
    {
        $this->districts = District::query()->where(['city_id' => $this->city_id])->with('city')->orderBy('id')->get(['id', 'city_id', 'name']);
    }
}
