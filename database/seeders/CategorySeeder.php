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
            ['id' => 1, 'name' => 'Koko Dewasa/Anak'],
            ['id' => 2, 'name' => 'Gamis Dewasa/Anak'],
            ['id' => 3, 'name' => 'Baju Muslim Pria'],
            ['id' => 4, 'name' => 'Baju Muslim Wanita'],
            ['id' => 5, 'name' => 'Baju Muslim Anak'],
            ['id' => 6, 'name' => 'Aksesoris Muslim'],
            ['id' => 7, 'name' => 'Sepatu Muslim'],
            ['id' => 8, 'name' => 'Tas Muslim'],
            ['id' => 9, 'name' => 'Pakaian Dalam Muslim'],
            ['id' => 10, 'name' => 'Pakaian Tidur Muslim'],
            ['id' => 11, 'name' => 'Pakaian Renang Muslim'],
            ['id' => 12, 'name' => 'Pakaian Olahraga Muslim'],
            ['id' => 13, 'name' => 'Pakaian Formal Muslim'],
            ['id' => 14, 'name' => 'Pakaian Santai Muslim'],
        ]);
    }
}
