<?php

namespace App\Livewire\City;

use App\Models\City;
use App\Services\CityService;
use App\Services\RoleService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class CityEdit extends Component
{
    use LivewireAlert;

    public null|City $city;

    public null|string $name;

    public bool $status = true;

    protected CityService $cityService;

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

    public function mount($id = null, City $city)
    {
        if(!is_null($id)) {
            $this->city = $city->whereId($id)->first();
            $this->name = $this->city->name;
        }
        else{
            return $this->redirect(route('cities.list'));
        }
    }

    public function render()
    {
        return view('livewire.city.city-edit');
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
