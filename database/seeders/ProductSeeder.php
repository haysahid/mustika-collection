<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
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

        foreach ($products as $productData) {
            // Ensure color exists or create it
            if (!isset($productData['color_id']) && isset($productData['color'])) {
                // Check if color exists by name or hex_color, otherwise create it
                $existingColor = Color::where('name', $productData['color']['name'])
                    ->orWhere('hex_code', $productData['color']['hex_code'])
                    ->first();

                if ($existingColor) {
                    $productData['color_id'] = $existingColor->id;
                } else {
                    // Create a new color if it doesn't exist
                    $productData['color_id'] = Color::create([
                        'name' => $productData['color']['name'],
                        'hex_code' => $productData['color']['hex_code'],
                    ])->id;
                }
            }

            // Ensure brand exists or create it
            if (!isset($productData['brand_id']) && isset($productData['brand'])) {
                $brand = Brand::firstOrCreate(
                    ['name' => $productData['brand']['name']]
                );
                $productData['brand_id'] = $brand->id;
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
                'color_id' => $productData['color_id'],
                'brand_id' => $productData['brand_id'],
                'store_id' => $productData['store_id'],
            ]);

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
                foreach ($productData['images'] as $image) {
                    // If image is a URL, download it and store it
                    if (filter_var($image['image'], FILTER_VALIDATE_URL)) {
                        $imageContents = @file_get_contents($image['image']);
                        if ($imageContents !== false) {
                            $imagePath = 'product/' . basename($image['image']);
                            Storage::put($imagePath, $imageContents);
                            $image['image'] = $imagePath;
                        } else {
                            continue;
                        }
                    }

                    $product->images()->create(['image' => $image['image']]);
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
