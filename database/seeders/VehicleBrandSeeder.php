<?php

namespace Database\Seeders;

use App\Models\VehicleBrand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->alert('Araç markaları ekleniyor...');
        $datas = [
            ["name" => "FORD"],
            ["name" => "MERCEDES"],
            ["name" => "RENAULT"],
        ];
        foreach ($datas as $value) {
            VehicleBrand::create($value);
        }
        $this->command->alert('Araç markaları eklendi.');
    }
}
