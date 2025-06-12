<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', 5);
        $sortBy = $request->input('order_by', 'created_at');
        $sortDirection = $request->input('order_direction', 'desc');
        $search = $request->input('search');

        $products = Product::query();

        if ($search) {
            $products->where('name', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        }
        $products->orderBy($sortBy, $sortDirection);
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
        return Inertia::render('Admin/Product/AddProduct');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
