<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ShippingMethod;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected $origin = 455; // Kabupaten Tangerang
    protected $weight = 1000; // 1000 gram (1 kg)
    protected $courier = 'jne'; // Courier service

    protected $provinces = [
        ['province_id' => '11', 'province' => 'Aceh'],
        ['province_id' => '51', 'province' => 'Bali'],
        ['province_id' => '36', 'province' => 'Banten'],
        ['province_id' => '17', 'province' => 'Bengkulu'],
        ['province_id' => '34', 'province' => 'DI Yogyakarta'],
        ['province_id' => '31', 'province' => 'DKI Jakarta'],
        ['province_id' => '75', 'province' => 'Gorontalo'],
        ['province_id' => '15', 'province' => 'Jambi'],
        ['province_id' => '32', 'province' => 'Jawa Barat'],
        ['province_id' => '33', 'province' => 'Jawa Tengah'],
        ['province_id' => '35', 'province' => 'Jawa Timur'],
        ['province_id' => '61', 'province' => 'Kalimantan Barat'],
        ['province_id' => '63', 'province' => 'Kalimantan Selatan'],
        ['province_id' => '62', 'province' => 'Kalimantan Tengah'],
        ['province_id' => '64', 'province' => 'Kalimantan Timur'],
        ['province_id' => '65', 'province' => 'Kalimantan Utara'],
        ['province_id' => '19', 'province' => 'Kepulauan Bangka Belitung'],
        ['province_id' => '21', 'province' => 'Kepulauan Riau'],
        ['province_id' => '18', 'province' => 'Lampung'],
        ['province_id' => '81', 'province' => 'Maluku'],
        ['province_id' => '82', 'province' => 'Maluku Utara'],
        ['province_id' => '52', 'province' => 'Nusa Tenggara Barat'],
        ['province_id' => '53', 'province' => 'Nusa Tenggara Timur'],
        ['province_id' => '91', 'province' => 'Papua'],
        ['province_id' => '92', 'province' => 'Papua Barat'],
        ['province_id' => '96', 'province' => 'Papua Barat Daya'],
        ['province_id' => '95', 'province' => 'Papua Pegunungan'],
        ['province_id' => '93', 'province' => 'Papua Selatan'],
        ['province_id' => '94', 'province' => 'Papua Tengah'],
        ['province_id' => '14', 'province' => 'Riau'],
        ['province_id' => '76', 'province' => 'Sulawesi Barat'],
        ['province_id' => '73', 'province' => 'Sulawesi Selatan'],
        ['province_id' => '72', 'province' => 'Sulawesi Tengah'],
        ['province_id' => '74', 'province' => 'Sulawesi Tenggara'],
        ['province_id' => '71', 'province' => 'Sulawesi Utara'],
        ['province_id' => '13', 'province' => 'Sumatera Barat'],
        ['province_id' => '16', 'province' => 'Sumatera Selatan'],
        ['province_id' => '12', 'province' => 'Sumatera Utara'],
    ];

    protected function getCities($province_id)
    {
        $fileContent = file_get_contents(__DIR__ . '/data_kota_kabupaten.json');
        $citiesData = json_decode($fileContent, true);

        $cities = [];

        $uniqueCityIdCounter = 1;
        foreach ($citiesData as &$city) {
            $provinceName = $city['province']['province_name'] ?? null;
            $cities[] = [
                'city_id' => (string)$uniqueCityIdCounter++,
                'province_id' => $this->provinces[array_search($provinceName, array_column($this->provinces, 'province'))]['province_id'] ?? null,
                'city_name' => $city['city_name'],
                'province' => $provinceName,
                'type' => $city['type'] ?? null,
                'postal_code' => $city['postal_code'] ?? null,
            ];
        }

        // Filter cities by province_id
        return array_values(array_filter($cities, fn($city) => $city['province_id'] === $province_id));
    }

    protected function getShipping($origin, $destination, $weight, $courier)
    {
        return [
            'value' => 38000,
            'etd' => '4-5',
            'note' => ''
        ];

        $cacheKey = "rajaongkir_shipping_cost_{$origin}_{$destination}_{$weight}_{$courier}";

        $shippingCost = cache()->remember($cacheKey, 60 * 60, function () use ($origin, $destination, $weight, $courier) {
            $client = new Client();
            $response = $client->post('https://api-sandbox.collaborator.komerce.id/starter/cost', [
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
        return ResponseFormatter::success(
            $this->provinces,
            'Daftar provinsi berhasil diambil'
        );

        // Deprecated code, kept for reference
        try {
            $provinces = [
                ['province_id' => '11', 'province_name' => 'Jakarta'],

            ];

            $cacheKey = 'rajaongkir_provinces';
            $provinces = cache()->remember($cacheKey, 60 * 60, function () {
                $client = new Client();
                $response = $client->get('https://api-sandbox.collaborator.komerce.id/starter/province', [
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
        $cacheKey = 'rajaongkir_cities_' . $request->input('province_id');
        $cities = cache()->remember($cacheKey, 60 * 60, function () use ($request) {
            return $this->getCities($request->input('province_id'));
        });

        return ResponseFormatter::success(
            $cities,
            'Daftar kota berhasil diambil'
        );

        // Deprecated code, kept for reference
        try {
            $provinceId = $request->input('province_id');
            $cacheKey = 'rajaongkir_cities_' . $provinceId;
            $cities = cache()->remember($cacheKey, 60 * 60, function () use ($provinceId) {
                $client = new Client();
                $response = $client->get('https://api-sandbox.collaborator.komerce.id/starter/city', [
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
            Log::error('Failed to retrieve shipping cost: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request_data' => $request->all(),
            ]);

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
            $shippingEstimate = $shipping['etd'] ?? null;
        } else {
            $provinceId = null;
            $provinceName = null;
            $cityId = null;
            $cityName = null;
            $address = null;
            $shippingCost = 0;
            $shippingEstimate = null;
        }

        try {
            DB::beginTransaction();

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
                'shipping_estimate' => $shippingEstimate,
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

                if ($shippingMethod->slug === 'courier') {
                    $itemDetails[] = [
                        'id' => 'shipping',
                        'price' => $shippingCost,
                        'quantity' => 1,
                        'name' => 'Biaya Pengiriman',
                        'brand' => null,
                        'merchant_name' => 'Mustika Collection',
                        'url' => null,
                    ];
                }

                $customer = User::find(Auth::id());

                $grossAmount = $total;

                $invoice = Invoice::create([
                    'transaction_id' => $transaction->id,
                    'code' => 'INV-' . date('YmdHis'),
                    'amount' => $total,
                    'tax' => 0,
                    'due_date' =>  now()->addDays(1),
                    'snap_token' => null,
                ]);

                // Create snap token for Midtrans
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

            Log::error('Checkout failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request_data' => $request->all(),
            ]);

            return ResponseFormatter::error(
                'Checkout failed' . $e->getMessage(),
                500
            );
        }
    }

    public function cancelOrder(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|integer|exists:invoices,id',
        ]);

        try {
            DB::beginTransaction();

            $invoice = Invoice::findOrFail($validated['invoice_id']);
            $transaction = Transaction::findOrFail($invoice->transaction_id);

            // Check if transaction is already paid
            if ($transaction->status === 'paid') {
                return ResponseFormatter::error(
                    'Transaksi sudah dibayar, tidak dapat dibatalkan',
                    400
                );
            }

            // Update transaction status to cancelled
            $transaction->status = 'cancelled';
            $transaction->save();

            DB::commit();

            return ResponseFormatter::success(
                null,
                'Pesanan berhasil dibatalkan',
                200
            );
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Cancel order failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'invoice_id' => $validated['invoice_id'],
            ]);

            return ResponseFormatter::error(
                'Gagal membatalkan pesanan: ' . $e->getMessage(),
                500
            );
        }
    }

    public function confirmPayment(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|integer|exists:invoices,id',
        ]);

        try {
            DB::beginTransaction();

            $invoice = Invoice::findOrFail($validated['invoice_id']);
            $transaction = Transaction::with('payment_method')->findOrFail($invoice->transaction_id);

            // Check midtrans payment status
            if ($transaction->payment_method->slug === 'transfer') {
                \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
                \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
                \Midtrans\Config::$is3ds = true;

                $response = (object) \Midtrans\Transaction::status($transaction->code);

                if ($response->transaction_status !== 'settlement') {
                    throw new Exception('Pembayaran belum terkonfirmasi');
                }

                // Create payment record
                Payment::create([
                    'invoice_id' => $invoice->id,
                    'transaction_id' => $transaction->id,
                    'payment_method_id' => $transaction->payment_method_id,
                    'amount' => $invoice->amount,
                    'midtrans_response' => json_encode($response),
                    'status' => 'completed',
                ]);

                // Update invoice paid_at
                $invoice->paid_at = now();
                $invoice->save();

                // Update transaction status
                $transaction->paid_at = now();
                $transaction->status = 'paid';
                $transaction->save();
            }

            DB::commit();

            return ResponseFormatter::success(
                null,
                'Pembayaran berhasil dikonfirmasi',
                200
            );
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Confirm payment failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'invoice_id' => $validated['invoice_id'],
            ]);

            return ResponseFormatter::error(
                'Gagal mengonfirmasi pembayaran: ' . $e->getMessage(),
                500
            );
        }
    }

    public function changeStatus(Request $request)
    {
        $validated = $request->validate([
            'transaction_id' => 'required|integer|exists:transactions,id',
            'status' => 'required|string|in:pending,paid,processing,completed,cancelled',
        ]);

        try {
            DB::beginTransaction();

            $transaction = Transaction::findOrFail($validated['transaction_id']);
            $transaction->status = $validated['status'];
            $transaction->save();

            DB::commit();

            return ResponseFormatter::success(
                $transaction,
                'Status transaksi berhasil diubah',
                200
            );
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Change status failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'transaction_id' => $validated['transaction_id'],
            ]);

            return ResponseFormatter::error(
                'Gagal mengubah status transaksi: ' . $e->getMessage(),
                500
            );
        }
    }
}
