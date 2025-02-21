<?php

namespace App\Livewire\Landlord\City;

use App\Models\Landlord\LandlordCity;
use App\Services\Landlord\LandlordCityService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class CityEdit extends Component
{
    use LivewireAlert;

    public null|Model $city;

    public null|string $name;
    public null|int $plate;

    public bool $status = true;

    protected LandlordCityService $cityService;

    /**
     * List of add/edit form rules
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                Rule::unique('cities')->ignore($this->city),
            ],
            'status' => [
                'in:true,false,null,0,1,active,passive,',
                'nullable',
            ],
        ];
    }

    protected $messages = [
        'name.required' => 'Şehir adını yazınız.',
        'name.unique' => 'Bu şehir adı zaten kullanılmaktadır.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function mount($id = null, LandlordCity $city)
    {
        if(!is_null($id)) {
            $this->city = $city->whereId($id)->first();
            $this->name = $this->city->name;
            $this->plate = $this->city->plate;
        }
        else{
            return $this->redirect(route('cities.list'));
        }
    }

    public function render()
    {
        return view('livewire.landlord.city.city-edit');
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
            $this->city->name = $this->name;
            $this->city->plate = $this->plate;

            $this->city->status = ($this->status == false ? 0 : 1);
            $this->city->save();

            $msg = 'Şehir güncellendi.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
        } catch (\Exception $exception) {
            $error = "Şehir güncellenemedi. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
