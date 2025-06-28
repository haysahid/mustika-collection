<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function syncCart(Request $request)
    {
        $validated = $request->validate([
            'cart_items' => 'required|array',
            'cart_items.*.product_id' => 'required|integer',
            'cart_items.*.variant_id' => 'required|integer',
            'cart_items.*.quantity' => 'required|integer|min:1',
            'cart_items.*.image' => 'nullable|string',
            'cart_items.*.created_at' => 'nullable|string',
            'cart_items.*.updated_at' => 'nullable|string',
            'cart_items.*.selected' => 'nullable|boolean',
        ]);

        try {
            $cartItems = $validated['cart_items'];
            $updateCartItems = [];

            foreach ($cartItems as $key => $item) {
                $product = Product::find($item['product_id']);
                if (!$product) continue;

                $variant = ProductVariant::find($item['variant_id']);
                if (!$variant) continue;

                $updatedItem = $item;

                $updatedItem['variant'] = $variant;
                $updatedItem['image'] = $variant->images->first()->image ?? $product->images->first()->image;

                $updateCartItems[$key] = $updatedItem;
            }

            return ResponseFormatter::success(
                $updateCartItems,
                'Keranjang berhasil disinkronisasi'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Gagal menyinkronkan keranjang',
                500
            );
        }
    }

    public function provinces(Request $request)
    {
        try {
            $cacheKey = 'rajaongkir_provinces';
            $provinces = cache()->remember($cacheKey, 60 * 60, function () {
                $client = new Client();
                $response = $client->get('https://api.rajaongkir.com/starter/province', [
                    'headers' => [
                        'key' => env('RAJAONGKIR_API_KEY'),
                    ],
                ]);
                return json_decode($response->getBody()->getContents())->rajaongkir->results;
            });

            return ResponseFormatter::success(
                $provinces,
                'Data retrieved successfully'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Failed to retrieve data',
                500
            );
        }
    }

    public function cities(Request $request)
    {
        try {
            $provinceId = $request->input('province_id');
            $cacheKey = 'rajaongkir_cities_' . $provinceId;
            $cities = cache()->remember($cacheKey, 60 * 60, function () use ($provinceId) {
                $client = new Client();
                $response = $client->get('https://api.rajaongkir.com/starter/city', [
                    'query' => ['province' => $provinceId],
                    'headers' => [
                        'key' => env('RAJAONGKIR_API_KEY'),
                    ],
                ]);
                return json_decode($response->getBody()->getContents())->rajaongkir->results;
            });

            return ResponseFormatter::success(
                $cities,
                'Data retrieved successfully'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Failed to retrieve data',
                500
            );
        }
    }

    public function shippingCost(Request $request)
    {
        $validated = $request->validate([
            'destination' => 'required|integer',
        ]);

        try {
            $origin = 455;
            $weight = 1000;
            $courier = 'jne';
            $destination = $validated['destination'];
            $cacheKey = "rajaongkir_shipping_cost_{$origin}_{$destination}_{$weight}_{$courier}";

            $shippingCost = cache()->remember($cacheKey, 60 * 60, function () use ($origin, $destination, $weight, $courier) {
                $client = new Client();
                $response = $client->post('https://api.rajaongkir.com/starter/cost', [
                    'headers' => [
                        'key' => env('RAJAONGKIR_API_KEY'),
                    ],
                    'form_params' => [
                        'origin' => $origin,
                        'destination' => $destination,
                        'weight' => $weight,
                        'courier' => $courier,
                    ],
                ]);

                $data = json_decode($response->getBody()->getContents(), true);
                return $data['rajaongkir']['results'][0]['costs'][0]['cost'][0] ?? null;
            });

            return ResponseFormatter::success(
                $shippingCost,
                'Shipping cost retrieved successfully'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Failed to retrieve shipping cost',
                500
            );
        }
    }
}
