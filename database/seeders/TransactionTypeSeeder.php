<?php

namespace Database\Seeders;

use App\Models\TransactionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TransactionType::insert([
            [
                'name' => 'Pembelian',
                'slug' => 'purchase',
                'description' => 'Transaksi pembelian barang.',
                'effect_on_stock' => 'inbound',
            ],
            [
                'name' => 'Penjualan',
                'slug' => 'sale',
                'description' => 'Transaksi penjualan barang ke pelanggan.',
                'effect_on_stock' => 'outbound',
            ],
            [
                'name' => 'Retur Supplier',
                'slug' => 'return_to_supplier',
                'description' => 'Transaksi pengembalian barang ke supplier.',
                'effect_on_stock' => 'outbound',
            ],
            [
                'name' => 'Retur',
                'slug' => 'return_from_customer',
                'description' => 'Transaksi pengembalian barang dari pelanggan.',
                'effect_on_stock' => 'inbound',
            ],
            [
                'name' => 'Barang Hilang',
                'slug' => 'damaged_out',
                'description' => 'Transaksi kehilangan atau kerusakan barang.',
                'effect_on_stock' => 'outbound',
            ],
            [
                'name' => 'Penggunaan Internal',
                'slug' => 'internal_use',
                'description' => 'Transaksi penggunaan barang untuk keperluan internal.',
                'effect_on_stock' => 'outbound',
            ],
            [
                'name' => 'Penyesuaian Stok Masuk',
                'slug' => 'adjustment_in',
                'description' => 'Transaksi penyesuaian stok masuk.',
                'effect_on_stock' => 'inbound',
            ],
            [
                'name' => 'Penyesuaian Stok Keluar',
                'slug' => 'adjustment_out',
                'description' => 'Transaksi penyesuaian stok keluar.',
                'effect_on_stock' => 'outbound',
            ],
        ]);
    }
}
