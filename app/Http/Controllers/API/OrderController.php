<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ShippingMethod;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected $origin = 455; // Kabupaten Tangerang
    protected $weight = 1000; // 1000 gram (1 kg)
    protected $courier = 'jne'; // Courier service

    protected function getShipping($origin, $destination, $weight, $courier)
    {
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

        if (!$shippingCost) {
            throw new Exception('Gagal mendapatkan biaya pengiriman');
        }

        return $shippingCost;
    }

    protected function createMidtransSnapToken($orderId, $itemDetails, $customer, $grossAmount)
    {
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $customer->name,
                'last_name' => '',
                'email' => $customer->email,
                'phone' => $customer->phone,
            ],
        ];

        return \Midtrans\Snap::getSnapToken($params);
    }

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
            $origin = $this->origin;
            $weight = $this->weight;
            $courier = $this->courier;
            $destination = $validated['destination'];

            $shipping = $this->getShipping($origin, $destination, $weight, $courier);

            return ResponseFormatter::success(
                $shipping,
                'Shipping cost retrieved successfully'
            );
        } catch (Exception $e) {
            return ResponseFormatter::error(
                'Failed to retrieve shipping cost',
                500
            );
        }
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'cart_items' => 'required|array',
            'cart_items.*.product_id' => 'required|integer|exists:products,id',
            'cart_items.*.variant_id' => 'required|integer|exists:product_variants,id',
            'cart_items.*.quantity' => 'required|integer|min:1',
            'payment_method_id' => 'required|integer|exists:payment_methods,id',
            'shipping_method_id' => 'required|integer|exists:shipping_methods,id',
            'province_id' => 'nullable|integer',
            'province_name' => 'nullable|string',
            'city_id' => 'nullable|integer',
            'city_name' => 'nullable|string',
            'address' => 'nullable|string',
            'note' => 'nullable|string',
        ], [
            'cart_items.*.product_id.exists' => 'Produk tidak ditemukan',
            'cart_items.*.variant_id.exists' => 'Varian produk tidak ditemukan',
            'cart_items.*.quantity.min' => 'Jumlah produk minimal 1',
            'payment_method_id.exists' => 'Metode pembayaran tidak ditemukan',
            'shipping_method_id.exists' => 'Metode pengiriman tidak ditemukan',
            'province_id.integer' => 'ID provinsi harus berupa angka',
            'province_name.string' => 'Nama provinsi harus berupa string',
            'city_id.integer' => 'ID kota harus berupa angka',
            'city_name.string' => 'Nama kota harus berupa string',
            'address.string' => 'Alamat harus berupa string',
            'note.string' => 'Catatan harus berupa string',
        ]);

        try {
            DB::beginTransaction();

            $paymentMethod = PaymentMethod::findOrFail($validated['payment_method_id']);
            $shippingMethod = ShippingMethod::findOrFail($validated['shipping_method_id']);

            if ($shippingMethod->slug === 'courier') {
                $shippingData = $request->validate([
                    'province_id' => 'required|integer',
                    'province_name' => 'required|string',
                    'city_id' => 'required|integer',
                    'city_name' => 'required|string',
                    'address' => 'required|string',
                ], [
                    'province_id.required' => 'ID provinsi harus diisi',
                    'province_id.integer' => 'ID provinsi harus berupa angka',
                    'province_name.required' => 'Nama provinsi harus diisi',
                    'province_name.string' => 'Nama provinsi harus berupa string',
                    'city_id.required' => 'ID kota tujuan harus diisi',
                    'city_id.integer' => 'ID kota tujuan harus berupa angka',
                    'city_name.required' => 'Nama kota tujuan harus diisi',
                    'city_name.string' => 'Nama kota tujuan harus berupa string',
                    'address.required' => 'Alamat harus diisi',
                    'address.string' => 'Alamat harus berupa string',
                ]);

                // Get shipping cost
                $origin = $this->origin;
                $weight = $this->weight;
                $courier = $this->courier;
                $destination = $shippingData['city_id'];
                $shipping = $this->getShipping($origin, $destination, $weight, $courier);

                $provinceId = $shippingData['province_id'] ?? null;
                $provinceName = $shippingData['province_name'] ?? null;
                $cityId = $shippingData['city_id'] ?? null;
                $cityName = $shippingData['city_name'] ?? null;
                $address = $shippingData['address'] ?? null;
                $shippingCost = $shipping['value'] ?? 0;
            } else {
                $provinceId = null;
                $provinceName = null;
                $cityId = null;
                $cityName = null;
                $address = null;
                $shippingCost = 0;
            }

            // Create transaction
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'type_id' => 2, // sale
                'code' => 'SL-' . date('YmdHis'),
                'note' => $validated['note'] ?? null,
                'payment_method_id' => $validated['payment_method_id'],
                'shipping_method_id' => $validated['shipping_method_id'],
                'province_id' => $provinceId,
                'province_name' => $provinceName,
                'city_id' => $cityId,
                'city_name' => $cityName,
                'address' => $address,
                'shipping_cost' => $shippingCost,
                'status' => 'pending',
            ]);

            // Create transaction items
            $subTotal = 0;
            foreach ($validated['cart_items'] as $item) {
                $variant = ProductVariant::findOrFail($item['variant_id']);
                $itemSubTotal = $item['quantity'] * $variant->final_selling_price;
                $subTotal += $itemSubTotal;

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'variant_id' => $variant->id,
                    'quantity' => $item['quantity'],
                    'unit_base_price' => $variant->base_selling_price,
                    'unit_discount_type' => $variant->discount_type,
                    'unit_discount' => $variant->discount,
                    'unit_final_price' => $variant->final_selling_price,
                    'subtotal' => $itemSubTotal,
                ]);
            }

            // Calculate total
            $total = $subTotal + $shippingCost;

            // Create invoice
            if ($paymentMethod->slug == 'transfer') {
                $itemDetails = TransactionItem::with(
                    [
                        'variant.product' => function ($query) {
                            $query->with('brand', 'categories');
                        },
                    ]
                )->where('transaction_id', $transaction->id)->get()->map(function ($item) {
                    return [
                        'id' => $item->variant_id,
                        'price' => $item->unit_final_price,
                        'quantity' => $item->quantity,
                        'name' => $item->variant->sku,
                        'brand' => $item->variant->product->brand->name ?? null,
                        'merchant_name' => 'Mustika Collection',
                        'url' => $item->variant->product->url,
                    ];
                })->toArray();

                $customer = User::find(Auth::id());

                $grossAmount = $total;

                $invoice = Invoice::create([
                    'transaction_id' => $transaction->id,
                    'code' => 'INV-' . date('YmdHis'),
                    'amount' => $total,
                    'tax' => 0,
                    'due_date' =>  now()->addDays(1),
                    'snap_token' => null, // No snap token for transfer payments
                ]);

                $snapToken = $this->createMidtransSnapToken(
                    $transaction->code,
                    $itemDetails,
                    $customer,
                    $grossAmount
                );

                if (!$snapToken) {
                    throw new Exception('Gagal mendapatkan token Snap');
                }

                // Update invoice with snap token
                $invoice->snap_token = $snapToken;
                $invoice->save();
            } else {
                $invoice = Invoice::create([
                    'transaction_id' => $transaction->id,
                    'code' => 'INV-' . date('YmdHis'),
                    'amount' => $total,
                    'tax' => 0,
                    'due_date' => now()->addDays(1),
                ]);
            }

            DB::commit();

            return ResponseFormatter::success(
                $invoice,
                'Pesanan berhasil dibuat',
                201
            );
        } catch (Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error(
                'Checkout failed' . $e->getMessage(),
                500
            );
        }
    }
}
