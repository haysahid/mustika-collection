<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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
    public function show(Store $store)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Store $store)
    {
        $store = Store::with([
            'advantages',
            'social_links',
        ])->first();

        return Inertia::render('Admin/StoreInfo', [
            'store' => $store,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'address' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:20',
                'advantages' => 'array',
                'social_links' => 'array',
            ],
            [
                'name.required' => 'Nama toko harus diisi.',
                'email.email' => 'Format email tidak valid.',
                'phone.max' => 'Nomor telepon tidak boleh lebih dari 20 karakter.',
            ]
        );

        try {
            DB::beginTransaction();

            $store = Store::firstOrFail();

            $store->update($request->only([
                'name',
                'description',
                'address',
                'email',
                'phone',
            ]));

            // Update advantages
            if ($request->has('advantages')) {
                $currentAdvantages = $store->advantages()->pluck('id')->toArray();
                foreach ($request->input('advantages') as $advantage) {
                    if (isset($advantage['id']) && in_array($advantage['id'], $currentAdvantages)) {
                        // Update existing advantage
                        $store->advantages()->where('id', $advantage['id'])->update([
                            'name' => $advantage['name'],
                            'description' => $advantage['description'] ?? null,
                        ]);
                    } else {
                        // Create new advantage
                        $store->advantages()->create([
                            'store_id' => $store->id,
                            'name' => $advantage['name'],
                            'description' => $advantage['description'] ?? null,
                        ]);
                    }
                }
            }

            // Update social links
            if ($request->has('social_links')) {
                $currentLinks = $store->social_links()->pluck('id')->toArray();
                foreach ($request->input('social_links') as $link) {
                    if (isset($link['id']) && in_array($link['id'], $currentLinks)) {
                        // Update existing social link
                        $store->social_links()->where('id', $link['id'])->update([
                            'url' => $link['url'],
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.store.edit')
                ->with('success', 'Informasi toko berhasil diperbarui.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memperbarui informasi toko: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Store $store)
    {
        //
    }
}
