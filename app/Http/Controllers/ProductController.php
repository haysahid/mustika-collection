<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', 5);
        $orderBy = $request->input('order_by', 'created_at');
        $orderDirection = $request->input('order_direction', 'desc');
        $search = $request->input('search');

        $brands = $request->input('brands');
        $colors = $request->input('colors');
        $categories = $request->input('categories');
        $sizes = $request->input('sizes');

        $products = Product::query();

        if ($brands) {
            $products->whereIn('brand_id', $brands);
        }

        if ($colors) {
            $products->whereIn('color_id', $colors);
        }

        if ($categories) {
            $products->whereHas('categories', function ($query) use ($categories) {
                $query->whereIn('category_id', $categories);
            });
        }

        if ($sizes) {
            $products->whereHas('sizes', function ($query) use ($sizes) {
                $query->whereIn('size_id', $sizes);
            });
        }

        if ($search) {
            $products->where('name', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        }

        $products->orderBy($orderBy, $orderDirection);
        $products->with(['brand', 'color', 'categories', 'sizes', 'images',]);
        $products->get();

        return Inertia::render('Admin/Product', [
            'products' => $products->paginate($limit),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::class::get();
        $categories = Category::get();
        $sizes = Size::get();
        $colors = Color::get();

        return Inertia::render('Admin/Product/AddProduct', [
            'brands' => $brands,
            'categories' => $categories,
            'sizes' => $sizes,
            'colors' => $colors,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'nullable|string|max:100',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'material' => 'nullable|string',
            'selling_price' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'stock' => 'required|integer',
            'min_order' => 'nullable|integer',
            'unit' => 'nullable|string|max:100',
            'color_id' => 'required|exists:colors,id',
            'brand_id' => 'required|exists:brands,id',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'sizes' => 'nullable|array',
            'sizes.*' => 'exists:sizes,id',
            'images' => 'nullable|array',
            'images.*' => 'file|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'code.string' => 'Kode produk harus berupa string.',
            'code.max' => 'Kode produk tidak boleh lebih dari 100 karakter.',
            'name.required' => 'Nama produk harus diisi.',
            'name.string' => 'Nama produk harus berupa string.',
            'name.max' => 'Nama produk tidak boleh lebih dari 255 karakter.',
            'description.string' => 'Deskripsi produk harus berupa string.',
            'material.string' => 'Bahan produk harus berupa string.',
            'selling_price.required' => 'Harga jual harus diisi.',
            'selling_price.numeric' => 'Harga jual harus berupa angka.',
            'discount.numeric' => 'Diskon harus berupa angka.',
            'stock.required' => 'Stok harus diisi.',
            'stock.integer' => 'Stok harus berupa bilangan bulat.',
            'min_order.integer' => 'Jumlah pesanan minimum harus berupa bilangan bulat.',
            'unit.string' => 'Satuan harus berupa string.',
            'unit.max' => 'Satuan tidak boleh lebih dari 100 karakter.',
            'color_id.required' => 'Warna produk harus dipilih.',
            'color_id.exists' => 'Warna yang dipilih tidak valid.',
            'brand_id.required' => 'Merek produk harus dipilih.',
            'brand_id.exists' => 'Merek yang dipilih tidak valid.',
            'categories.array' => 'Kategori harus berupa array.',
            'categories.*.exists' => 'Kategori yang dipilih tidak valid.',
            'sizes.array' => 'Ukuran harus berupa array.',
            'sizes.*.exists' => 'Ukuran yang dipilih tidak valid.',
            'images.array' => 'Gambar harus berupa array.',
            'images.*.file' => 'Gambar harus berupa file.',
            'images.*.mimes' => 'Gambar harus berupa file dengan format: jpg, jpeg, png, webp.',
            'images.*.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
        ]);

        try {
            DB::beginTransaction();
            $product = Product::create([
                ...$validated,
                'slug' => str($validated['name'])->slug(),
                'store_id' => 1,
            ]);

            $product->categories()->attach($validated['categories']);
            $product->sizes()->attach($validated['sizes']);

            if (isset($validated['images'])) {
                foreach ($validated['images'] as $key => $image) {
                    $imagePath = $image->store('product');
                    $product->images()->create([
                        'image' => $imagePath,
                        'order' => $key,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.product')
                ->with('success', 'Produk berhasil dibuat.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Gagal membuat produk: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product->load(['brand', 'color', 'categories', 'sizes', 'images']);

        $brands = Brand::class::get();
        $categories = Category::get();
        $sizes = Size::get();
        $colors = Color::get();

        return Inertia::render('Admin/Product/EditProduct', [
            'product' => $product,
            'brands' => $brands,
            'categories' => $categories,
            'sizes' => $sizes,
            'colors' => $colors,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'code' => 'nullable|string|max:100',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'material' => 'nullable|string',
            'selling_price' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'stock' => 'required|integer',
            'min_order' => 'nullable|integer',
            'unit' => 'nullable|string|max:100',
            'color_id' => 'required|exists:colors,id',
            'brand_id' => 'required|exists:brands,id',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'sizes' => 'nullable|array',
            'sizes.*' => 'exists:sizes,id',
        ], [
            'code.required' => 'Kode produk harus diisi.',
            'name.required' => 'Nama produk harus diisi.',
            'description.string' => 'Deskripsi harus berupa string.',
            'material.string' => 'Material harus berupa string.',
            'selling_price.required' => 'Harga jual harus diisi.',
            'selling_price.numeric' => 'Harga jual harus berupa angka.',
            'discount.numeric' => 'Diskon harus berupa angka.',
            'stock.required' => 'Stok harus diisi.',
            'stock.integer' => 'Stok harus berupa bilangan bulat.',
            'min_order.integer' => 'Jumlah pesanan minimum harus berupa bilangan bulat.',
            'unit.string' => 'Satuan harus berupa string.',
            'unit.max' => 'Satuan tidak boleh lebih dari 100 karakter.',
            'color_id.required' => 'Warna produk harus dipilih.',
            'color_id.exists' => 'Warna yang dipilih tidak valid.',
            'brand_id.required' => 'Merek produk harus dipilih.',
            'brand_id.exists' => 'Merek yang dipilih tidak valid.',
            'categories.array' => 'Kategori harus berupa array.',
            'categories.*.exists' => 'Kategori yang dipilih tidak valid.',
            'sizes.array' => 'Ukuran harus berupa array.',
            'sizes.*.exists' => 'Ukuran yang dipilih tidak valid.',
        ]);

        try {
            DB::beginTransaction();
            $product->update([
                ...$validated,
                'slug' => str($validated['name'])->slug(),
            ]);

            $product->categories()->sync($validated['categories']);
            $product->sizes()->sync($validated['sizes']);

            DB::commit();

            return redirect()->route('admin.product')
                ->with('success', 'Produk berhasil diperbarui.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Gagal memperbarui produk: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();

            // Delete product images
            foreach ($product->images as $image) {
                if ($image->image) {
                    Storage::delete($image->image);
                }
                $image->delete();
            }

            // Delete product
            $product->delete();

            DB::commit();

            return redirect()->route('admin.product')
                ->with('success', 'Produk berhasil dihapus.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Gagal menghapus produk: ' . $e->getMessage()]);
        }
    }
}
