<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            BrandSeeder::class,
            ColorSeeder::class,
            CategorySeeder::class,
            SizeSeeder::class,
        ]);

        $products = [
            // Sarimbit
            [
                'code' => 'PRD001',
                'name' => 'Gaia Rose Rayya Series Nibras Couple Keluarga Muslim Terkini 2025',
                'slug' => 'gaia-rose-rayya-series-nibras-couple-keluarga-muslim-terkini-2025',
                'description' => 'Gaia Rose Rayya Series Nibras Couple Keluarga Muslim Terkini 2025 adalah koleksi terbaru yang dirancang khusus untuk keluarga Muslim modern. Dengan desain elegan dan bahan berkualitas tinggi, setelan ini memberikan kenyamanan dan gaya yang sempurna untuk berbagai acara keluarga. Tersedia dalam berbagai ukuran dan warna, cocok untuk pria, wanita, dan anak-anak. Dapatkan penampilan serasi bersama keluarga Anda dengan Gaia Rose Rayya Series.',
                'material' => 'Katun',
                'selling_price' => 399000,
                'discount' => 15,
                'stock' => 20,
                'min_order' => 1,
                'unit' => 'set',
                'color_id' => 1,
                'brand_id' => 2,
                'store_id' => 1,
                'categories' => [
                    ['id' => 1],
                    ['id' => 2],
                ],
                'sizes' => [
                    ['id' => 1],
                    ['id' => 2],
                    ['id' => 3],
                ],
                'images' => [
                    ['image' => 'product/product_1.png'],
                ],
            ],
            // Koko
            [
                'code' => 'PRD002',
                'name' => 'Koko Premium Pria Modern',
                'slug' => 'koko-premium-pria-modern',
                'description' => 'Koko Premium Pria Modern adalah pilihan sempurna untuk pria yang menginginkan penampilan stylish dan elegan. Terbuat dari bahan berkualitas tinggi, koko ini nyaman dipakai sepanjang hari. Desainnya yang modern membuatnya cocok untuk berbagai acara, baik formal maupun santai.',
                'material' => 'Katun',
                'selling_price' => 250000,
                'discount' => 10,
                'stock' => 15,
                'min_order' => 1,
                'unit' => 'pcs',
                'color_id' => 2,
                'brand_id' => 1,
                'store_id' => 1,
                'categories' => [
                    ['id' => 1],
                    ['id' => 3],
                ],
                'sizes' => [
                    ['id' => 2],
                    ['id' => 3],
                    ['id' => 4],
                ],
                'images' => [
                    ['image' => 'product/product_1.png'],
                ],
            ],
            // Gamis
            [
                'code' => 'PRD003',
                'name' => 'Gamis Elegan Wanita Modern',
                'slug' => 'gamis-elegan-wanita-modern',
                'description' => 'Gamis Elegan Wanita Modern adalah pilihan ideal untuk wanita yang menginginkan penampilan anggun dan modis. Dengan desain yang stylish dan bahan yang nyaman, gamis ini cocok untuk berbagai acara, baik formal maupun kasual. Tersedia dalam berbagai ukuran dan warna.',
                'material' => 'Satin',
                'selling_price' => 300000,
                'discount' => 20,
                'stock' => 10,
                'min_order' => 1,
                'unit' => 'pcs',
                'color_id' => 3,
                'brand_id' => 2,
                'store_id' => 1,
                'categories' => [
                    ['id' => 2],
                    ['id' => 4],
                ],
                'sizes' => [
                    ['id' => 1],
                    ['id' => 2],
                    ['id' => 3],
                ],
                'images' => [
                    ['image' => 'product/product_1.png'],
                ],
            ],
            // Aksesoris
            [
                'code' => 'PRD004',
                'name' => 'Aksesoris Muslim Stylish',
                'slug' => 'aksesoris-muslim-stylish',
                'description' => 'Aksesoris Muslim Stylish adalah pelengkap sempurna untuk penampilan Anda. Dengan desain yang unik dan bahan berkualitas, aksesoris ini menambah sentuhan elegan pada busana Muslim Anda. Cocok untuk berbagai acara, baik formal maupun santai.',
                'material' => 'Logam',
                'selling_price' => 75000,
                'discount' => 5,
                'stock' => 50,
                'min_order' => 1,
                'unit' => 'pcs',
                'color_id' => 4,
                'brand_id' => 3,
                'store_id' => 1,
                'categories' => [
                    ['id' => 5],
                ],
                'sizes' => [],
                'images' => [
                    ['image' => 'product/product_1.png'],
                ],
            ],
            // Sepatu
            [
                'code' => 'PRD005',
                'name' => 'Sepatu Muslim Casual',
                'slug' => 'sepatu-muslim-casual',
                'description' => 'Sepatu Muslim Casual adalah pilihan tepat untuk Anda yang menginginkan kenyamanan dan gaya. Dengan desain yang modern dan bahan yang berkualitas, sepatu ini cocok untuk penggunaan sehari-hari. Tersedia dalam berbagai ukuran.',
                'material' => 'Kulit Sintetis',
                'selling_price' => 450000,
                'discount' => 15,
                'stock' => 30,
                'min_order' => 1,
                'unit' => 'pasang',
                'color_id' => 5,
                'brand_id' => 4,
                'store_id' => 1,
                'categories' => [
                    ['id' => 6],
                ],
                'sizes' => [
                    ['id' => 2],
                    ['id' => 3],
                    ['id' => 4],
                ],
                'images' => [
                    ['image' => 'product/product_1.png'],
                ],
            ],
            // Tas
            [
                'code' => 'PRD006',
                'name' => 'Tas Kulit Premium',
                'slug' => 'tas-kulit-premium',
                'description' => 'Tas Kulit Premium adalah tas berkualitas tinggi yang dirancang untuk memberikan kesan elegan dan profesional. Terbuat dari kulit asli, tas ini tahan lama dan cocok untuk berbagai kesempatan, baik formal maupun kasual.',
                'material' => 'Kulit Asli',
                'selling_price' => 350000,
                'discount' => 20,
                'stock' => 10,
                'min_order' => 1,
                'unit' => 'pcs',
                'color_id' => 2,
                'brand_id' => 1,
                'store_id' => 1,
                'categories' => [
                    ['id' => 7],
                ],
                'sizes' => [],
                'images' => [
                    ['image' => 'product/product_1.png'],
                ],
            ],
        ];

        foreach ($products as $productData) {
            $product = Product::create([
                'code' => $productData['code'],
                'name' => $productData['name'],
                'slug' => $productData['slug'],
                'description' => $productData['description'],
                'material' => $productData['material'],
                'selling_price' => $productData['selling_price'],
                'discount' => $productData['discount'],
                'stock' => $productData['stock'],
                'min_order' => $productData['min_order'],
                'unit' => $productData['unit'],
                'color_id' => $productData['color_id'],
                'brand_id' => $productData['brand_id'],
                'store_id' => $productData['store_id'],
            ]);

            // Attach categories
            if (isset($productData['categories'])) {
                foreach ($productData['categories'] as $category) {
                    $product->categories()->attach($category['id']);
                }
            }

            // Attach sizes
            if (isset($productData['sizes'])) {
                foreach ($productData['sizes'] as $size) {
                    $product->sizes()->attach($size['id']);
                }
            }

            // Add images
            if (isset($productData['images'])) {
                foreach ($productData['images'] as $image) {
                    $product->images()->create(['image' => $image['image']]);
                }
            }
        }
    }
}
