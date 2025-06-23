<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Color::insert([
            [
                'name' => 'Merah',
                'hex_code' => '#FF0000',
            ],
            [
                'name' => 'Biru',
                'hex_code' => '#0000FF',
            ],
            [
                'name' => 'Hijau',
                'hex_code' => '#00FF00',
            ],
            [
                'name' => 'Kuning',
                'hex_code' => '#FFFF00',
            ],
            [
                'name' => 'Hitam',
                'hex_code' => '#000000',
            ],
            [
                'name' => 'Putih',
                'hex_code' => '#FFFFFF',
            ],
            [
                'name' => 'Ungu',
                'hex_code' => '#800080',
            ],
            [
                'name' => 'Abu-abu',
                'hex_code' => '#808080',
            ],
            [
                'name' => 'Coklat',
                'hex_code' => '#A52A2A',
            ],
            [
                'name' => 'Oranye',
                'hex_code' => '#FFA500',
            ],
            [
                'name' => 'Pink',
                'hex_code' => '#FFC0CB',
            ],
            [
                'name' => 'Emas',
                'hex_code' => '#FFD700',
            ],
            [
                'name' => 'Olive',
                'hex_code' => '#808000',
            ],
        ]);
    }
}
