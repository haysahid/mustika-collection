<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Size::insert([
            ['name' => 'S'],
            ['name' => 'M'],
            ['name' => 'L'],
            ['name' => 'XL'],
            ['name' => 'XXL'],
        ]);
    }
}
