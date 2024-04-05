<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentMethodRequest;
use App\Http\Requests\UpdatePaymentMethodRequest;
use App\Http\Resources\PaymentMethodResource;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return PaymentMethodResource::collection(PaymentMethod::all());
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
    public function store(StorePaymentMethodRequest $request)
    {
        //
        $method =  PaymentMethod::create([
            'method' => $request->input('method'),
            'details' => $request->input('details'),
        ]);
        return new PaymentMethodResource($method);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $paymentMethod)
    {
        //
        $method =  PaymentMethod::findOrFail($paymentMethod);
        return new PaymentMethodResource($method);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentMethodRequest $request, int $paymentMethod)
    {
        //
        $method =  PaymentMethod::findOrFail($paymentMethod);
        $method->update([
            'method' => $request->input('method')
        ]);
        return new PaymentMethodResource($method);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $paymentMethod)
    {
        //
        $method =  PaymentMethod::findOrFail($paymentMethod);
        $method->delete();
        return response(['message' => 'method deleted successfully']);
    }
}
