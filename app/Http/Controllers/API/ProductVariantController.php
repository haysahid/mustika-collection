<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantImage;
use App\Models\Size;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductVariantController extends Controller
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
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'motif' => 'required|string|max:255',
            'color_id' => 'required|integer|exists:colors,id',
            'size_id' => 'required|integer|exists:sizes,id',
            'material' => 'required|string|max:255',
            'purchase_price' => 'nullable|numeric|min:0',
            'base_selling_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'current_stock_level' => 'required|integer',
            'unit' => 'required|string|max:100',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ], [
            'product_id.required' => 'ID produk harus diisi.',
            'product_id.exists' => 'Produk tidak ditemukan.',
            'motif.required' => 'Motif harus diisi.',
            'color_id.required' => 'Warna harus dipilih.',
            'color_id.exists' => 'Warna yang dipilih tidak valid.',
            'size_id.required' => 'Ukuran harus dipilih.',
            'size_id.exists' => 'Ukuran yang dipilih tidak valid.',
            'material.required' => 'Bahan harus diisi.',
            'purchase_price.numeric' => 'Harga beli harus berupa angka.',
            'purchase_price.min' => 'Harga beli tidak boleh kurang dari 0.',
            'base_selling_price.required' => 'Harga jual harus diisi.',
            'base_selling_price.numeric' => 'Harga jual harus berupa angka.',
            'base_selling_price.min' => 'Harga jual tidak boleh kurang dari 0.',
            'images.*.required' => 'Gambar harus diunggah.',
            'images.*.image' => 'File yang diunggah harus berupa gambar.',
            'images.*.mimes' => 'Gambar harus berformat: jpeg, png, jpg, gif, svg, webp.',
            'images.*.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        try {
            DB::beginTransaction();

            $product = Product::find($validated['product_id']);
            $color = Color::find($validated['color_id']);
            $size = Size::find($validated['size_id']);

            $variant = ProductVariant::create([
                'product_id' => $validated['product_id'],
                'sku' => $product->sku_prefix . '_' . strtoupper(str_replace(' ', '', $validated['motif'] . '_' . $color->name . '_' . $size->name)),
                'slug' => str($product->name . '-' . $validated['motif'] . '-' . $color->name . '-' . $size->name)->slug(),
                'motif' => $validated['motif'],
                'color_id' => $color->id,
                'size_id' => $size->id,
                'material' => $validated['material'],
                'purchase_price' => $validated['purchase_price'] ?? $validated['base_selling_price'],
                'base_selling_price' => $validated['base_selling_price'],
                'discount_type' => 'percentage',
                'discount' => $validated['discount'] ?? 0,
                'final_selling_price' => $validated['base_selling_price'] - ($validated['base_selling_price'] * ($validated['discount'] ?? 0) / 100),
                'current_stock_level' => $validated['current_stock_level'],
                'unit' => $validated['unit'],
            ]);

            if ($validated['images']) {
                foreach ($validated['images'] as $key => $image) {
                    ProductVariantImage::create([
                        'product_variant_id' => $variant->id,
                        'product_id' => $variant->product_id,
                        'image' => $image->store('product'),
                        'order' => $key,
                    ]);
                }
            }

            DB::commit();

            $variant = ProductVariant::with(['color', 'size', 'images'])
                ->find($variant->id);

            return ResponseFormatter::success(
                $variant,
                'Varian produk berhasil dibuat.',
                201
            );
        } catch (Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error(
                'Gagal membuat varian produk.',
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
        $validated = $request->validate([
            'motif' => 'required|string|max:255',
            'color_id' => 'required|integer|exists:colors,id',
            'size_id' => 'required|integer|exists:sizes,id',
            'material' => 'required|string|max:255',
            'purchase_price' => 'nullable|numeric|min:0',
            'base_selling_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'current_stock_level' => 'required|integer',
            'unit' => 'required|string|max:100',
        ], [
            'motif.required' => 'Motif harus diisi.',
            'color_id.required' => 'Warna harus dipilih.',
            'color_id.exists' => 'Warna yang dipilih tidak valid.',
            'size_id.required' => 'Ukuran harus dipilih.',
            'size_id.exists' => 'Ukuran yang dipilih tidak valid.',
            'material.required' => 'Bahan harus diisi.',
            'purchase_price.numeric' => 'Harga beli harus berupa angka.',
            'purchase_price.min' => 'Harga beli tidak boleh kurang dari 0.',
            'base_selling_price.required' => 'Harga jual harus diisi.',
            'base_selling_price.numeric' => 'Harga jual harus berupa angka.',
            'base_selling_price.min' => 'Harga jual tidak boleh kurang dari 0.',
            'discount.numeric' => 'Diskon harus berupa angka.',
            'discount.min' => 'Diskon tidak boleh kurang dari 0.',
            'current_stock_level.required' => 'Stok saat ini harus diisi.',
            'current_stock_level.integer' => 'Stok saat ini harus berupa angka.',
            'unit.required' => 'Satuan harus diisi.',
            'unit.max' => 'Satuan tidak boleh lebih dari 100 karakter.',
        ]);

        try {
            DB::beginTransaction();

            $color = Color::find($validated['color_id']);
            $size = Size::find($validated['size_id']);

            $variant = ProductVariant::with(['product'])->findOrFail($id);
            $variant->sku = strtoupper(str_replace(' ', '', $variant->product->sku_prefix . '_' . $validated['motif'] . '_' . $color->name . '_' . $size->name));
            $variant->slug = str($variant->product->name . '-' . $validated['motif'] . '-' . $color->name . '-' . $size->name)->slug();
            $variant->motif = $validated['motif'];
            $variant->color_id = $validated['color_id'];
            $variant->size_id = $validated['size_id'];
            $variant->material = $validated['material'];

            if ($validated['purchase_price'] !== null) {
                $variant->purchase_price = $validated['purchase_price'];
            }

            $variant->base_selling_price = $validated['base_selling_price'];
            $variant->discount = $validated['discount'] ?? 0;
            $variant->final_selling_price = $variant->base_selling_price - ($variant->base_selling_price * ($variant->discount / 100));
            $variant->current_stock_level = $validated['current_stock_level'];
            $variant->unit = $validated['unit'];

            $variant->save();

            DB::commit();

            return ResponseFormatter::success(
                $variant,
                'Product variant updated successfully.'
            );
        } catch (Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error(
                'Failed to update product variant.',
                500
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $variant = ProductVariant::findOrFail($id);
            $variant->delete();

            // Delete associated images
            ProductVariantImage::where('product_variant_id', $id)->each(function ($image) {
                // Check if the file used by another product
                $otherImages = ProductVariantImage::where('image', $image->image)
                    ->where('id', '!=', $image->id)
                    ->count();

                if ($otherImages === 0) {
                    // Delete the image file
                    Storage::delete($image->image);
                }

                $image->delete();
            });

            DB::commit();

            return ResponseFormatter::success(
                null,
                'Variant produk berhasil dihapus.'
            );
        } catch (Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error(
                'Gagal menghapus varian produk.',
                500
            );
        }
    }
}
