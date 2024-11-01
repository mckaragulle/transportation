<?php

namespace App\Livewire;

use App\Imports\BankImport;
use App\Imports\CityImport;
use App\Imports\VehicleBrandsImport;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class Dashboard extends Component
{

    public $name = 'Mustafa';

    public $title = 'Başlık';

    public function render()
    {
        return view('livewire.dashboard');
    }

    public function importBrand(){
        $file = Storage::path('public/VehicleBrands.xlsx');
        Excel::import(new VehicleBrandsImport(), $file);
    }
    
    public function importCity(){
        $file = Storage::path('public/city.xlsx');
        Excel::import(new CityImport(), $file);
    }
    
    public function importBank(){
        $file = Storage::path('public/bank.xlsx');
        Excel::import(new BankImport(), $file);
    }
}
