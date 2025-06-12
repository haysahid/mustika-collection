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
        ]);
    }
}
