<?php

namespace App\Imports;

use App\Models\VehicleBrand;
use App\Models\VehicleModel;
use App\Models\VehicleTicket;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class VehicleBrandsImport implements ShouldQueue, ToModel, WithBatchInserts, WithChunkReading, WithHeadingRow
{
    protected ?VehicleBrand $vehicleBrand = null;
    protected ?VehicleTicket $vehicleTicket = null;
    protected ?VehicleModel $vehicleModel = null;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            $this->vehicleBrand = VehicleBrand::firstOrCreate([                
                'name' => $row["marka_adi"]
            ]);
    
            $this->vehicleTicket = VehicleTicket::firstOrCreate([
                'vehicle_brand_id' => $this->vehicleBrand->id,
                'name' => $row["tip_adi"],
            ]);
    
            $years = [
                2024, 
                2023, 
                2022, 
                2021, 
                2020, 
                2019, 
                2018, 
                2017, 
                2016, 
                2015, 
                2014, 
                2013, 
                2012, 
                2011, 
                2010
            ];
    
            foreach($years as $year){
                if(isset($row[$year]) && $row[$year] > 0){
                    VehicleModel::firstOrCreate([
                        'vehicle_brand_id' => $this->vehicleBrand->id,
                        'vehicle_ticket_id' => $this->vehicleTicket->id,
                        'name' => $year,
                        'insurance_number' => $row[$year],
                    ]);
                }
            } 
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
