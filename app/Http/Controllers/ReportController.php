<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Repositories\ReportRepository;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $reportType = $request->input('report_type', 'sale');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $brand = $request->input('brand');

        $reportRepository = new ReportRepository();

        if ($reportType === 'purchase') {
            $data = $reportRepository->getPurchaseReport($startDate, $endDate);
        } elseif ($reportType === 'stock') {
            $data = $reportRepository->getStockReport($brand);
            $data['brands'] = Brand::orderBy('name', 'asc')->get();
        } else {
            $data = $reportRepository->getSalesReport($startDate, $endDate);
        }

        return Inertia::render('Admin/Report', $data);
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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

    public function reportPreview(Request $request)
    {
        $reportType = $request->input('report_type', 'sale');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $brand = $request->input('brand');

        $reportRepository = new ReportRepository();

        if ($reportType === 'purchase') {
            $data = $reportRepository->getPurchaseReport($startDate, $endDate);
        } elseif ($reportType === 'stock') {
            $data = $reportRepository->getStockReport($brand);
        } else {
            $data = $reportRepository->getSalesReport($startDate, $endDate);
        }

        return view('reports.report', $data);
    }
}
