<?php

namespace App\Jobs;

use App\Imports\CityImport;
use App\Imports\VehicleBrandsImport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Multitenancy\Jobs\NotTenantAware;

class LandlordExcelImportJob implements ShouldQueue, NotTenantAware
{
    use Queueable;

    public $filePath;
    public $model;

    /**
     * Create a new job instance.
     */
    public function __construct($model, $filePath)
    {
        $this->model = $model;
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Excel::import(new $this->model(), $this->filePath);  
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
