<?php

namespace App\Http\Controllers;

use App\Models\StoreCertificate;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StoreCertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/Certificate');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Certificate/AddCertificate');
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
    public function show(StoreCertificate $storeCertificate)
    {
        return Inertia::render('Admin/Certificate/EditCertificate');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StoreCertificate $storeCertificate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StoreCertificate $storeCertificate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StoreCertificate $storeCertificate)
    {
        //
    }
}
