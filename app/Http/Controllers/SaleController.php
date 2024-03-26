<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Http\Resources\SaleResource;
use App\Models\Sale;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return SaleResource::collection(Sale::paginate(20));
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
    public function store(StoreSaleRequest $request)
    {
        //
        $sale = Sale::create([
            'amount' => $request->input('amount'),
            'service_id' => $request->input('serviceId'),
            'payment_method_id' => $request->input('paymentMethodId'),
            'narration' => $request->input('narration'),
        ]);
        return new SaleResource($sale);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $sale)
    {
        //
        $sale =  Sale::findOrFail($sale);
        return new SaleResource($sale);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSaleRequest $request, int $sale)
    {
        //
        $sale =  Sale::findOrFail($sale);
        $sale->update([
            'amount' => $request->input('amount'),
            'service_id' => $request->input('serviceId'),
            'payment_method_id' => $request->input('paymentMethodId'),
            'narration' => $request->input('narration'),
        ]);
        return new SaleResource($sale);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $sale)
    {
        //
        $sale =  Sale::findOrFail($sale);
        $sale->delete();
        return response(['message' => 'transaction deleted successfully']);
    }
}
