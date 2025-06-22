<?php

namespace Database\Seeders;

use App\Models\Platform;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Platform::insert([
            ['name' => 'Shopee', 'icon' => 'platform/shopee.png'],
            ['name' => 'Lazada', 'icon' => 'platform/lazada.png'],
            ['name' => 'Tokopedia', 'icon' => 'platform/tokopedia.png'],
            ['name' => 'Blibli', 'icon' => 'platform/blibli.png'],
            ['name' => 'JD.ID', 'icon' => 'platform/jd_id.png'],
            ['name' => 'Tiktok Shop', 'icon' => 'platform/tiktok_shop.png'],
            ['name' => 'Facebook Marketplace', 'icon' => 'platform/facebook_marketplace.png'],
        ]);
    }
}
