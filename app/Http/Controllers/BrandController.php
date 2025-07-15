<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $sortBy = $request->input('order_by', 'created_at');
        $sortDirection = $request->input('order_direction', 'desc');
        $search = $request->input('search');

        $brands = Brand::query();

        if ($search) {
            $brands->where('name', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        }

        $brands->orderBy($sortBy, $sortDirection);
        $brands->get();

        return Inertia::render('Admin/Brand', [
            'brands' => $brands->paginate($limit),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Brand/AddBrand');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        Brand::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'logo' => $request->file('logo') ? $request->file('logo')->store('brand') : null,
            'website' => url('catalog?brands=' . $validated['name']),
        ]);

        return redirect()->route('admin.brand')->with('success', 'Brand berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        return Inertia::render('Admin/Brand/EditBrand', [
            'brand' => $brand,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $brand->name = $validated['name'];
            $brand->description = $validated['description'] ?? null;
            $brand->website = url('catalog?brands=' . $validated['name']);

            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($brand->logo) {
                    Storage::delete($brand->logo);
                }
                $brand->logo = $request->file('logo')->store('brand');
            }

            $brand->save();

            DB::commit();

            return redirect()->route('admin.brand')->with('success', 'Brand berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal memperbarui brand: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        try {
            DB::beginTransaction();

            // Delete logo if exists
            if ($brand->logo) {
                Storage::delete($brand->logo);
            }

            $brand->delete();

            DB::commit();

            return redirect()->route('admin.brand')->with('success', 'Brand berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal menghapus brand: ' . $e->getMessage()]);
        }
    }
}
