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
        Brand::insert([
            [
                'name' => 'Brand A',
                'description' => 'Deskripsi Brand A',
                'logo' => 'brand/brand_1.png',
                'website' => 'https://brand-a.com',
            ],
            [
                'name' => 'Brand B',
                'description' => 'Deskripsi Brand B',
                'logo' => 'brand/brand_2.png',
                'website' => 'https://brand-b.com',
            ],
            [
                'name' => 'Brand C',
                'description' => 'Deskripsi Brand C',
                'logo' => 'brand/brand_3.png',
                'website' => 'https://brand-c.com',
            ],
            [
                'name' => 'Brand D',
                'description' => 'Deskripsi Brand D',
                'logo' => 'brand/brand_4.png',
                'website' => 'https://brand-d.com',
            ],
            [
                'name' => 'Brand E',
                'description' => 'Deskripsi Brand E',
                'logo' => 'brand/brand_5.png',
                'website' => 'https://brand-e.com',
            ],
        ]);
    }
}
