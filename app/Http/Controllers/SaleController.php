<?php

namespace App\Http\Controllers;

use App\Http\Enums\TransactionCategory;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Http\Resources\SaleResource;
use App\Http\Traits\HttpResponses;
use App\Models\PaymentMethod;
use App\Models\Sale;
use App\Models\Service;
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
        $sales = Sale::orderBy('created_at','desc')->paginate($request->input('perPage'));
        if($request->input('search')){
            $sales = Sale::where('narration','like',"%".$request->input('search')."%")->orderBy('created_at','desc')->paginate($request->input('perPage'));
        }

        return SaleResource::collection($sales);
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
        $request = $request->validated();
        $memberId =  null;
        if($request['memberId']){
            $memberId =  $request['memberId'];
        }

        $service =  Service::find($request['serviceId']);
        $paymentMethod = PaymentMethod::find($request['paymentMethodId']);

        $transaction = Transaction::create([
            'amount' => $request['amount'],
            'narration' => 'Payment for service '.$service['service'].' using '.$paymentMethod['method'],
            'type' => 'credit',
            'transaction_date' => date('Y-m-d', strtotime($request['paymentDate'])),
            'category' => TransactionCategory::SERVICE_INCOME,
            'payment_method_id' => $paymentMethod['id'],
        ]);

        //
        $sale = Sale::create([
            'amount' => $request['amount'],
            'service_id' => $request['serviceId'],
            'payment_method_id' => $paymentMethod['id'],
            'narration' => 'Payment for service '.$service['service'].' using '.$paymentMethod['method'],
            'member_id' => $memberId,
            'transaction_id' => $transaction['id'],
            'sale_date' => date('Y-m-d', strtotime($request['paymentDate']))
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
