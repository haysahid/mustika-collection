<?php

namespace App\Jobs;

use App\Models\ProductVariantImage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DownloadProductVariantImage implements ShouldQueue
{
    use Queueable, Dispatchable;

    protected $imageUrl;
    protected $productVariantId;
    protected $productId;
    protected $order;

    public function __construct($imageUrl, $productVariantId, $productId, $order)
    {
        $this->imageUrl = $imageUrl;
        $this->productVariantId = $productVariantId;
        $this->productId = $productId;
        $this->order = $order;
    }

    public function handle()
    {
        $basename = basename(parse_url($this->imageUrl, PHP_URL_PATH));
        if (!preg_match('/\.[a-zA-Z0-9]+$/', $basename)) {
            $basename .= '.jpg';
        }
        $imagePath = 'product/' . $basename;

        Log::info('----------------------------------');
        Log::info('Processing image: ' . $imagePath);

        // Check if the image already exists in folder
        $existingImage = Storage::exists($imagePath);

        if ($existingImage) {
            Log::info('Image already exists: ' . $imagePath);

            // Save image path to database
            ProductVariantImage::create([
                'product_variant_id' => $this->productVariantId,
                'product_id' => $this->productId,
                'image' => $imagePath,
                'order' => $this->order,
            ]);

            Log::info('Image path saved to database: ' . $imagePath);

            return;
        }

        Log::info('Downloading image async: ' . $this->imageUrl);

        $imageContents = @file_get_contents($this->imageUrl);
        if ($imageContents !== false) {
            Storage::put($imagePath, $imageContents);

            Log::info('Image downloaded and saved: ' . $imagePath);

            // Save to database
            ProductVariantImage::create([
                'product_variant_id' => $this->productVariantId,
                'product_id' => $this->productId,
                'image' => $imagePath,
                'order' => $this->order,
            ]);
        } else {
            Log::error('Failed to download image: ' . $this->imageUrl);
        }
    }
}
