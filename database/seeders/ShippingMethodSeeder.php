<?php

namespace Database\Seeders;

use App\Models\ShippingMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShippingMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ShippingMethod::insert([
            [
                'name' => 'Ambil di Toko',
                'slug' => 'pickup',
                'description' => 'Ambil barang langsung di toko.',
            ],
            [
                'name' => 'Kurir',
                'slug' => 'courier',
                'description' => 'Pengiriman menggunakan jasa kurir. Biaya tergantung lokasi dan berat barang. Estimasi waktu pengiriman 1-5 hari kerja.',
            ],
        ]);
    }
}
