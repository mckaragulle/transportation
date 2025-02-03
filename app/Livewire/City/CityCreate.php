<?php

namespace App\Livewire\City;

use App\Services\CityService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class CityCreate extends Component
{
    use LivewireAlert;

    public null|Collection $cities;

    public null|string $name;
    public null|int $plate;

    public bool $status = true;

    protected CityService $cityService;

    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'name' => [
            'required',
            'unique:cities'
        ],
        'status' => ['nullable', 'in:true,false,null,0,1,active,passive,'],
    ];

    protected $messages = [
        'name.required' => 'Şehir adını yazınız.',
        'name.unique' => 'Bu şehir adı zaten kullanılmaktadır.',
        'status.in' => 'Lütfen geçerli bir durum seçiniz.',
    ];

    public function render()
    {
        return view('livewire.city.city-create');
    }

    /**
     * store the user inputted student data in the students table
     *
     * @return void
     */
    public function store(CityService $cityService)
    {
        $this->validate();
        $this->cityService = $cityService;
        DB::beginTransaction();
        try {
            $city = $this->cityService->create([
                'name' => $this->name,
                'plate' => $this->plate,
                'status' => $this->status == false ? 0 : 1,
            ]);

            $this->dispatch('pg:eventRefresh-CityTable');
            $msg = 'Yeni şehir oluşturuldu.';
            session()->flash('message', $msg);
            $this->alert('success', $msg, ['position' => 'center']);
            DB::commit();
            $this->reset();
        } catch (\Exception $exception) {
            $error = "Şehir oluşturulamadı. {$exception->getMessage()}";
            session()->flash('error', $error);
            $this->alert('error', $error);
            Log::error($error);
            DB::rollBack();
        }
    }
}
