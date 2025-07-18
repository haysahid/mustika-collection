<?php

namespace App\Jobs;

use App\Models\ProductImage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DownloadProductImage implements ShouldQueue
{
    use Queueable, Dispatchable;

    protected $imageUrl;
    protected $productId;
    protected $order;

    public function __construct($imageUrl, $productId, $order)
    {
        $this->imageUrl = $imageUrl;
        $this->productId = $productId;
        $this->order = $order;
    }

    public function handle()
    {
        Log::info('Downloading image async: ' . $this->imageUrl);

        $imageContents = @file_get_contents($this->imageUrl);
        if ($imageContents !== false) {
            $basename = basename(parse_url($this->imageUrl, PHP_URL_PATH));
            if (!preg_match('/\.[a-zA-Z0-9]+$/', $basename)) {
                $basename .= '.jpg';
            }
            $imagePath = 'product/' . $basename;
            Storage::put($imagePath, $imageContents);

            // Simpan ke database
            ProductImage::create([
                'product_id' => $this->productId,
                'image' => $imagePath,
                'order' => $this->order,
            ]);
        }
    }
}
