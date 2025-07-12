<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with([
            'brand',
            'categories',
            'images',
            'links.platform',
            'variants.color',
            'variants.size',
            'variants.images',
        ])->find($id);

        if (!$product) {
            return ResponseFormatter::error(
                'Produk tidak ditemukan.',
                404
            );
        }

        return ResponseFormatter::success(
            $product,
            'Produk berhasil ditemukan.'
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
