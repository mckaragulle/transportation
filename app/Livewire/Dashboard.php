<?php

namespace App\Livewire;

use App\Imports\BankImport;
use App\Imports\CityImport;
use App\Imports\VehicleBrandsImport;
use App\Jobs\LandlordBankJob;
use App\Jobs\LandlordCityJob;
use App\Jobs\LandlordExcelImportJob;
use App\Models\Bank;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Spatie\Multitenancy\Models\Tenant;

class Dashboard extends Component
{
    use LivewireAlert;

    public function importBrand(){
        $className = VehicleBrandsImport::class;
        $filePath = Storage::path('public/VehicleBrands.xlsx');
        // Excel::import(new VehicleBrandsImport(), $file);
        LandlordExcelImportJob::dispatch($className, $filePath);
        $msg = "İçeri Aktarma Başladı.";
        $this->alert('success', $msg, ['position' => 'center']);
    }
    
    public function importCity(){
        $className = CityImport::class;
        $filePath = Storage::path('public/city.xlsx');
        LandlordExcelImportJob::dispatch($className, $filePath);
        $msg = "İçeri Aktarma Başladı.";
        $this->alert('success', $msg, ['position' => 'center']);
    }
    
    public function importBank(){
        $className = BankImport::class;
        $filePath = Storage::path('public/bank.xlsx');        
        // LandlordBankJob::dispatch($filePath);
        LandlordExcelImportJob::dispatch($className, $filePath);
        $msg = "İçeri Aktarma Başladı.";
        $this->alert('success', $msg, ['position' => 'center']);
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
