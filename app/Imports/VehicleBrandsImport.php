<?php

namespace App\Imports;

use App\Jobs\Tenant\TenantSyncDataJob;
use App\Models\Landlord\LandlordVehicleBrand;
use App\Models\Landlord\LandlordVehicleModel;
use App\Models\Landlord\LandlordVehicleTicket;
use App\Models\Tenant\VehicleBrand;
use App\Models\Tenant\VehicleModel;
use App\Models\Tenant\VehicleTicket;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Spatie\Multitenancy\Models\Tenant;

class VehicleBrandsImport implements ShouldQueue, ToModel, WithBatchInserts, WithChunkReading, WithHeadingRow
{
    protected ?LandlordVehicleBrand $vehicleBrand = null;
    protected ?LandlordVehicleTicket $vehicleTicket = null;
    protected ?LandlordVehicleModel $vehicleModel = null;

    /**
     * @param array $row
     *
     * @return void
     */
    public function model(array $row)
    {
        DB::beginTransaction();
        try {

            /**
             * Araç markası yoksa ekle ve job oluştur. Varsa mevcut araç markası getir.
             */
            $whereData = [
                'name' => $row["marka_adi"]
            ];
            $vehicleBrand = LandlordVehicleBrand::query();
            if(!$vehicleBrand->where($whereData)->exists()){
                $this->vehicleBrand = $vehicleBrand->create($whereData);
                Tenant::all()->eachCurrent(function(Tenant $tenant) {
                    $data = getTenantSyncDataJob($this->vehicleBrand);
                    TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'vehicle_brands', 'Araç Markaları Eklenirken Hata Oluştu.');
                });
            }
            $this->vehicleBrand = $vehicleBrand->where($whereData)->first();

            /**
             * Araç etiketi yoksa ekle ve job oluştur. Varsa mevcut araç etiketi getir.
             */

            $whereData = [
                'vehicle_brand_id' => $this->vehicleBrand->id,
                'name' => $row["tip_adi"],
            ];
            $vehicleTicket = LandlordVehicleTicket::query();
            if(!$vehicleTicket->where($whereData)->exists()){
                $this->vehicleTicket = $vehicleTicket->create($whereData);
                Tenant::all()->eachCurrent(function(Tenant $tenant) {
                    $data = getTenantSyncDataJob($this->vehicleTicket);
                    TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'vehicle_tickets', 'Araç Etiketleri Eklenirken Hata Oluştu.');
                });
            }
            $this->vehicleTicket = $vehicleTicket->where($whereData)->first();


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
                    $whereData = [
                        'vehicle_brand_id' => $this->vehicleBrand->id,
                        'vehicle_ticket_id' => $this->vehicleTicket->id,
                        'name' => $year,
                        'insurance_number' => $row[$year],
                    ];

                    $vehicleModel = LandlordVehicleModel::query();
                    if(!$vehicleModel->where($whereData)->exists()){
                        $this->vehicleModel = $vehicleModel->create($whereData);
                        Tenant::all()->eachCurrent(function(Tenant $tenant) use($whereData) {
                            $data = [
                                'status' => true
                            ];
                            TenantSyncDataJob::dispatch($tenant->id, $whereData, $data, 'vehicle_models', 'Araç Modelleri Eklenirken Hata Oluştu.');
                        });
                    }

                }
            }
            DB::commit();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
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
