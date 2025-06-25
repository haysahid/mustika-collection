<?php

namespace Database\Seeders;

use App\Jobs\DownloadProductImage;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

        $products = require __DIR__ . '/data/products.php';
        shuffle($products);

        foreach ($products as $productData) {
            $brandId = $productData['brand_id'];

            Log::info('Processing product: ' . $productData['code']);

            // Ensure brand exists or create it
            if (!isset($productData['brand_id']) && isset($productData['brand'])) {
                Log::info('Brand not found, creating brand: ' . $productData['brand']['name']);
                $brand = Brand::firstOrCreate(
                    ['name' => $productData['brand']['name']]
                );
                $brandId = $brand->id;
            }

            // Create the product
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
                'brand_id' => $brandId,
                'store_id' => $productData['store_id'],
            ]);

            // Attach colors
            if (isset($productData['colors'])) {
                foreach ($productData['colors'] as $color) {
                    if (isset($color['id'])) {
                        // If color ID is provided, attach it directly
                        $product->colors()->attach($color['id']);
                        continue;
                    }

                    // Check if color exists by name or hex_color, otherwise create it
                    $existingColor = Color::where('name', $color['name'])
                        ->orWhere('hex_code', $color['hex_code'])
                        ->first();

                    if ($existingColor) {
                        $product->colors()->attach($existingColor->id);
                    } else {
                        // Create a new color if it doesn't exist
                        $newColor = Color::create([
                            'name' => $color['name'],
                            'hex_code' => $color['hex_code'],
                        ]);

                        if ($newColor) {
                            $product->colors()->attach($newColor->id);
                        }
                    }
                }
            }

            // Attach categories
            if (isset($productData['categories'])) {
                foreach ($productData['categories'] as $category) {
                    if (isset($category['id'])) {
                        // If category ID is provided, attach it directly
                        $product->categories()->attach($category['id']);
                        continue;
                    }

                    // Check if category exists by name, otherwise create it
                    $categoryModel = Category::firstOrCreate(
                        ['name' => $category['name']],
                    );

                    if ($categoryModel) {
                        $product->categories()->attach($categoryModel->id);
                    }
                }
            }

            // Attach sizes
            if (isset($productData['sizes'])) {
                foreach ($productData['sizes'] as $size) {
                    if (isset($size['id'])) {
                        // If size ID is provided, attach it directly
                        $product->sizes()->attach($size['id']);
                        continue;
                    }

                    // Check if size exists by name, otherwise create it
                    $sizeModel = Size::firstOrCreate(
                        ['name' => $size['name']],
                    );

                    if ($sizeModel) {
                        $product->sizes()->attach($sizeModel->id);
                    }
                }
            }

            // Add images
            if (isset($productData['images'])) {
                foreach ($productData['images'] as $key => $image) {
                    // If image is a URL, download it and store it
                    if (filter_var($image['image'], FILTER_VALIDATE_URL)) {
                        DownloadProductImage::dispatch($image['image'], $product->id, $key);
                    } else {
                        $product->images()->create(
                            [
                                'image' => $image['image'],
                                'order' => $key
                            ]
                        );
                    }
                }
            }

            // Add links
            if (isset($productData['links'])) {
                foreach ($productData['links'] as $link) {
                    $product->links()->create([
                        'platform_id' => $link['platform_id'],
                        'url' => $link['url'],
                    ]);
                }
            }
        }
    }
}
