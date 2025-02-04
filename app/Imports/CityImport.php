<?php

namespace App\Imports;

use App\Jobs\Tenant\TenantCityJob;
use App\Jobs\Tenant\TenantDistrictJob;
use App\Jobs\Tenant\TenantLocalityJob;
use App\Jobs\Tenant\TenantNeighborhoodJob;
use App\Jobs\Tenant\TenantSyncDataJob;
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
use Spatie\Multitenancy\Jobs\NotTenantAware;
use Spatie\Multitenancy\Models\Tenant;

class CityImport implements NotTenantAware, ShouldQueue, ToModel, WithBatchInserts, WithChunkReading, WithHeadingRow
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

            /**
             * İl yoksa ekle ve job oluştur. Varsa mevcut ili getir.
             */
            $whereData = [
                'plate' => $id,
                'name' => $name
            ];
            $city = City::query();
            if(!$city->where($whereData)->exists()){
                $this->city = $city->create($whereData);
                Tenant::all()->eachCurrent(function(Tenant $tenant) {
                    $data = getTenantSyncDataJob($this->city);
                    TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'cities', 'Şehirler Eklenirken Hata Oluştu.');
                });
            }
            $this->city = $city->where($whereData)->first();


            /**
             * İlçe yoksa ekle ve job oluştur. Varsa mevcut ilçeyi getir.
             */
            $name = Str::trim($row["ilce"]);
            $whereData = [
                'city_id' => $this->city->id,
                'name' => $name
            ];
            $district = District::query();
            if(!$district->where($whereData)->exists()){
                $this->district = $district->create($whereData);
                Tenant::all()->eachCurrent(function(Tenant $tenant) {
                    $data = getTenantSyncDataJob($this->district);
                    TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'districts', 'İlçeler Eklenirken Hata Oluştu.')
                        ->delay(now()->addMinutes(5));
                });
            }
            $this->district = $district->where($whereData)->first();

            /**
             * Semt yoksa ekle ve job oluştur. Varsa mevcut semti getir.
             */
            $name = Str::trim($row["semt_bucak_belde"]);
            $whereData = [
                'city_id' => $this->city->id,
                'district_id' => $this->district->id,
                'name' => $name,
                'postcode' => $pk,
            ];

            $neighborhood = Neighborhood::query();
            if(!$neighborhood->where($whereData)->exists()){
                $this->neighborhood = $neighborhood->create($whereData);
                Tenant::all()->eachCurrent(function(Tenant $tenant) {
                    $data = getTenantSyncDataJob($this->neighborhood);
                    TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'neighborhoods', 'Semtler Eklenirken Hata Oluştu.')
                        ->delay(now()->addMinutes(10));
                });
            }
            $this->neighborhood = $neighborhood->where($whereData)->first();

            /**
             * Mahalle yoksa ekle ve job oluştur. Varsa mevcut mahalleyi getir.
             */
            $name = Str::trim($row["mahalle"]);
            $whereData = [
                'city_id' => $this->city->id,
                'district_id' => $this->district->id,
                'neighborhood_id' => $this->neighborhood->id,
                'name' => $name
            ];

            $locality = Locality::query();
            if(!$locality->where($whereData)->exists()){
                $this->locality = $locality->create($whereData);
                Tenant::all()->eachCurrent(function(Tenant $tenant) {
                    $data = getTenantSyncDataJob($this->locality);
                    TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'localities', 'Mahalleler Eklenirken Hata Oluştu.')
                        ->delay(now()->addMinutes(20));
                });
            }
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
