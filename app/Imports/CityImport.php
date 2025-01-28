<?php

namespace App\Imports;

use App\Models\City;
use App\Models\District;
use App\Models\Locality;
use App\Models\Neighborhood;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class CityImport implements ShouldQueue, ToModel, WithBatchInserts, WithChunkReading, WithHeadingRow
{
    public ?City $city = null;
    public ?District $district = null;
    public ?Neighborhood $neighborhood = null;
    public ?Locality $locality = null;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            $name = Str::trim($row["il"]);
            $pk = Str::trim($row["pk"]);
            $id = (int)Str::substr($pk, 0, 2);

            $this->city = City::firstOrCreate([                
                'plate' => $id,
                'name' => $name
            ]);
    
            $name = Str::trim($row["ilce"]);
            $this->district = District::firstOrCreate([
                'city_id' => $this->city->id,
                'name' => $name
            ]);
            
            $name = Str::trim($row["semt_bucak_belde"]);
            
            $this->neighborhood = Neighborhood::firstOrCreate([
                'city_id' => $this->city->id,
                'district_id' => $this->district->id,
                'name' => $name,
                'postcode' => $pk,
            ]);
            
            $name = Str::trim($row["mahalle"]);
            $this->locality = Locality::firstOrCreate([
                'city_id' => $this->city->id,
                'district_id' => $this->district->id,
                'neighborhood_id' => $this->neighborhood->id,
                'name' => $name
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
