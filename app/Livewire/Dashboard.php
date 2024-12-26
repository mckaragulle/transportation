<?php

namespace App\Livewire;

use App\Imports\BankImport;
use App\Imports\CityImport;
use App\Imports\VehicleBrandsImport;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class Dashboard extends Component
{
    use LivewireAlert;
    public $name = 'Mustafa';

    public $title = 'Başlık';

    public function render()
    {
        return view('livewire.dashboard');
    }

    public function importBrand(){
        $file = Storage::path('public/VehicleBrands.xlsx');
        Excel::import(new VehicleBrandsImport(), $file);
        $msg = "İçeri Aktarma Başladı.";
        $this->alert('success', $msg, ['position' => 'center']);
    }
    
    public function importCity(){
        $file = Storage::path('public/city.xlsx');
        Excel::import(new CityImport(), $file);
        $msg = "İçeri Aktarma Başladı.";
        $this->alert('success', $msg, ['position' => 'center']);
    }
    
    public function importBank(){
        $file = Storage::path('public/bank.xlsx');
        Excel::import(new BankImport(), $file);
        $msg = "İçeri Aktarma Başladı.";
        $this->alert('success', $msg, ['position' => 'center']);
    }
}
