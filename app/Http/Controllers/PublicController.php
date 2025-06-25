<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PublicController extends Controller
{
    public function home()
    {
        $store = Store::with([
            'advantages',
            'certificates' => function ($query) {
                $query->limit(5);
            },
            'social_links',
        ])->first();

        $brands = Brand::take(5)->get();

        $popularProducts = Product::with(['brand', 'categories', 'images'])->take(8)->get();

        return Inertia::render('Home', [
            'store' => $store,
            'brands' => $brands,
            'popularProducts' => $popularProducts,
        ]);
    }

    public function catalog(Request $request)
    {
        $store = Store::with([
            'advantages',
            'certificates' => function ($query) {
                $query->limit(5);
            },
            'social_links',
        ])->first();

        $limit = $request->input('limit', 10);
        $orderBy = $request->input('order_by', 'created_at');
        $orderDirection = $request->input('order_direction', 'desc');
        $search = $request->input('search');

        $brands = $request->input('brands');
        $colors = $request->input('colors');
        $categories = $request->input('categories');
        $sizes = $request->input('sizes');

        $products = Product::query();
        $products->with(['brand', 'colors', 'categories', 'sizes', 'images',]);

        if ($brands) {
            if (is_string($brands)) {
                $brands = explode(',', $brands);
                $brands = Brand::whereIn('name', $brands)->pluck('id')->toArray();
            }
            $products->whereIn('brand_id', $brands);
        }

        if ($colors) {
            if (is_string($colors)) {
                $colors = explode(',', $colors);
                $colors = Color::whereIn('name', $colors)->pluck('id')->toArray();
            }
            $products->whereIn('color_id', $colors);
        }

        if ($categories) {
            if (is_string($categories)) {
                $categories = explode(',', $categories);
                Log::info('Categories input:', ['categories' => $categories]);
                $categories = Category::whereIn('name', $categories)->pluck('id')->toArray();
            }
            Log::info('Categories input:', ['categories' => $categories]);
            $products->whereHas('categories', function ($query) use ($categories) {
                $query->whereIn('category_id', $categories);
            });
        }

        if ($sizes) {
            if (is_string($sizes)) {
                $sizes = explode(',', $sizes);
                $sizes = Size::whereIn('name', $sizes)->pluck('id')->toArray();
            }
            $products->whereHas('sizes', function ($query) use ($sizes) {
                $query->whereIn('size_id', $sizes);
            });
        }

        if ($search) {
            $search = trim($search);
            $products->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
                $query->orWhereHas('brand', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
                $query->orWhereHas('categories', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
                $query->orWhereHas('colors', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            });
        }

        $products->orderBy($orderBy, $orderDirection);

        $products->get();

        return Inertia::render('Catalog', [
            'store' => $store,
            'products' => $products->paginate($limit),
            'filters' => [
                'brands' => Brand::get(),
                'colors' => Color::get(),
                'categories' => Category::orderBy('name', 'asc')->get(),
                'sizes' => Size::get(),
            ],
        ]);
    }

    public function productDetail($slug)
    {
        $store = Store::with([
            'advantages',
            'certificates' => function ($query) {
                $query->limit(5);
            },
            'social_links',
        ])->first();

        $product = Product::where('slug', $slug)
            ->with(['brand', 'colors', 'categories', 'sizes', 'images', 'links.platform'])
            ->firstOrFail();

        $relatedProducts = Product::where('id', '!=', $product->id)
            ->with(['brand', 'categories', 'images'])
            ->take(4)
            ->get();

        return Inertia::render('ProductDetail', [
            'store' => $store,
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
