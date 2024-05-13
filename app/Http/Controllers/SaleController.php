<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Http\Resources\SaleResource;
use App\Http\Traits\HttpResponses;
use App\Models\Sale;
use App\Models\Transaction;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $limit = $request->query('limit', 15);
        return SaleResource::collection(Sale::paginate($limit));
    }


    public function counts()
    {
        //
        $daily =  Sale::getDailySales();
        $monthly = Sale::getMonthlySales();
        return $this->success(['daily' => $daily, 'monthly' => $monthly]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSaleRequest $request)
    {
        $memberId =  NULL;
        if($request->input('memberId')){
            $memberId =  $request->input('memberId');
        }

        $transaction = Transaction::create([
            'amount' => $request->amount,
            'narration' => $request->narration,
            'type' => 'credit'
        ]);

        //
        $sale = Sale::create([
            'amount' => $request->input('amount'),
            'service_id' => $request->input('serviceId'),
            'payment_method_id' => $request->input('paymentMethodId'),
            'narration' => $request->input('narration'),
            'member_id' => $memberId,
            'transaction_id' => $transaction['id']
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
