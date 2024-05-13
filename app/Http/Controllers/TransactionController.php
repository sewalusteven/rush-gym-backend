<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Http\Traits\HttpResponses;
use App\Models\Expense;
use App\Models\Member;
use App\Models\Sale;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TransactionController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $transactions = Transaction::orderBy('created_at','desc')->paginate($request->input('perPage'));
        if($request->input('search')){
            $transactions = Transaction::where('narration','like',"%".$request->input('search')."%")->orderBy('created_at','desc')->paginate($request->input('perPage'));
        }

        return TransactionResource::collection($transactions);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function counts()
    {
        //
        $daily =  Transaction::getDailyTransactions();
        $monthly = Transaction::getMonthlyTransactions();
        $totalBalance = Transaction::getBalance();

        return $this->success(['daily' => $daily, 'monthly' => $monthly, 'totalBalance' => $totalBalance]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        //
        $transaction = Transaction::create([
            'amount' => $request->amount,
            'narration' => $request->narration,
            'type' => $request->type
        ]);

        switch ($request->type){
            case 'credit':
                Sale::create([
                    'amount' => $request->input('amount'),
                    'service_id' => $request->input('serviceId'),
                    'payment_method_id' => $request->input('paymentMethodId'),
                    'narration' => $request->input('narration'),
                    'member_id' => null,
                    'transaction_id' => $transaction['id']
                ]);
                break;
            case 'debit':
                Expense::create([
                    'amount' => $request->amount,
                    'narration' => $request->narration,
                    'user_id' => Auth::id(),
                    'transaction_id' => $transaction['id']
                ]);
                break;
        }

        return $this->success($transaction,'transaction added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $transaction)
    {
        //
        return new TransactionResource(Transaction::findOrFail($transaction));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, int $transaction)
    {
        //
        $transaction =  Transaction::findOrFail($transaction);
        $transaction->update([
            'amount' => $request->amount,
            'narration' => $request->narration,
            'type' => $request->type
        ]);

        return $this->success($transaction,'transaction updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $transaction)
    {
        //
        $toBeDeleted =  Transaction::findOrFail($transaction);
        $toBeDeleted->delete();
        return $this->success($toBeDeleted, "transaction deleted");
    }
}
