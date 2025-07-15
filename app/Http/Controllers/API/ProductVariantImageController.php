<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\ProductVariantImage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductVariantImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'image' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            'order' => 'nullable|integer|min:0',
        ], [
            'product_variant_id.required' => 'ID varian produk harus diisi.',
            'product_variant_id.exists' => 'Varian produk tidak ditemukan.',
            'image.required' => 'Gambar varian produk harus diunggah.',
            'image.file' => 'Gambar harus berupa file.',
            'image.mimes' => 'Gambar harus berformat jpg, jpeg, png, atau webp.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
            'order.integer' => 'Urutan harus berupa angka.',
            'order.min' => 'Urutan tidak boleh kurang dari 0.',
        ]);

        try {
            DB::beginTransaction();

            $variant = ProductVariantImage::where('product_variant_id', $validatedData['product_variant_id'])->first();

            $lastImageOrder = ProductVariantImage::where('product_variant_id', $validatedData['product_variant_id'])
                ->max('order') ?? -1;

            $productVariantImage = ProductVariantImage::create([
                'product_variant_id' => $validatedData['product_variant_id'],
                'product_id' => $variant->product_id,
                'image' => $request->file('image')->store('product'),
                'order' => $validatedData['order'] ?? $lastImageOrder + 1,
            ]);

            DB::commit();

            return ResponseFormatter::success(
                $productVariantImage,
                'Gambar varian produk berhasil diunggah.',
                201
            );
        } catch (Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error(
                'Gagal mengunggah gambar varian produk.',
                500
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'order' => 'nullable|integer|min:0',
        ], [
            'image.file' => 'Gambar harus berupa file.',
            'image.mimes' => 'Gambar harus berformat jpg, jpeg, png, atau webp.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
            'order.integer' => 'Urutan harus berupa angka.',
            'order.min' => 'Urutan tidak boleh kurang dari 0.',
        ]);

        $variantImage = ProductVariantImage::findOrFail($id);

        if (!$variantImage) {
            return ResponseFormatter::error(
                'Gambar varian produk tidak ditemukan.',
                404
            );
        }

        try {
            DB::beginTransaction();

            if (isset($validatedData['image'])) {
                // Delete the old image file if it exists
                if ($variantImage->image) {
                    // Check if the file used by another product
                    $otherImages = ProductVariantImage::where('image', $variantImage->image)
                        ->where('id', '!=', $variantImage->id)
                        ->count();

                    if ($otherImages === 0) {
                        Storage::delete($variantImage->image);
                    }
                }

                $variantImage->image = $request->file('image')->store('product');
            }

            if (isset($validatedData['order'])) {
                $variantImage->order = $validatedData['order'];
            }

            $variantImage->save();

            DB::commit();

            return ResponseFormatter::success(
                $variantImage,
                'Gambar varian produk berhasil diperbarui.'
            );
        } catch (Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error(
                'Gagal memperbarui gambar varian produk.',
                500
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $variantImage = ProductVariantImage::find($id);

        if (!$variantImage) {
            return ResponseFormatter::error(
                'Gambar varian produk tidak ditemukan.',
                404
            );
        }

        try {
            DB::beginTransaction();

            // Check if the file used by another product
            $otherImages = ProductVariantImage::where('image', $variantImage->image)
                ->where('id', '!=', $variantImage->id)
                ->count();

            if ($otherImages === 0) {
                Storage::delete($variantImage->image);
            }

            $variantImage->delete();

            DB::commit();

            return ResponseFormatter::success(
                null,
                'Gambar varian produk berhasil dihapus.'
            );
        } catch (Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error(
                'Gagal menghapus gambar varian produk.',
                500
            );
        }
    }
}
