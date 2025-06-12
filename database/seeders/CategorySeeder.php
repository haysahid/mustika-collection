<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::insert([
            ['name' => 'Koko Dewasa/Anak'],
            ['name' => 'Gamis Dewasa/Anak'],
            ['name' => 'Baju Muslim Pria'],
            ['name' => 'Baju Muslim Wanita'],
            ['name' => 'Baju Muslim Anak'],
            ['name' => 'Aksesoris Muslim'],
            ['name' => 'Sepatu Muslim'],
            ['name' => 'Tas Muslim'],
            ['name' => 'Pakaian Dalam Muslim'],
            ['name' => 'Pakaian Tidur Muslim'],
            ['name' => 'Pakaian Renang Muslim'],
            ['name' => 'Pakaian Olahraga Muslim'],
            ['name' => 'Pakaian Formal Muslim'],
            ['name' => 'Pakaian Santai Muslim'],
        ]);
    }
}
