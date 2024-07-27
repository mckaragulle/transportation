<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->alert('Markalar ekleniyor...');
        $datas = [
            "MERCEDES",
            "WOLKVAGEN",
            "FORD",
        ];
        foreach ($datas as $key => $brand) {
            $area_id = Brand::firstOrCreate(['name' => $brand]);
        }
        $this->command->alert('Markalar eklendi.');
    }
}
