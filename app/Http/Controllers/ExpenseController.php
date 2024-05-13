<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Http\Resources\ExpenseResource;
use App\Http\Traits\HttpResponses;
use App\Models\Expense;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        return ExpenseResource::collection(Expense::paginate($request->perPage));
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
    public function store(StoreExpenseRequest $request)
    {
        //
        $transaction = Transaction::create([
            'amount' => $request->amount,
            'narration' => $request->narration,
            'type' => 'debit'
        ]);

        $expense = Expense::create([
            'amount' => $request->amount,
            'narration' => $request->narration,
            'user_id' => Auth::id(),
            'transaction_id' => $transaction['id']
        ]);



        return $this->success($expense,'expense added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpenseRequest $request, int $expense)
    {
        //
        $expense = Expense::findOrFail($expense);
        $expense->update([
            'amount' => $request->amount,
            'narration' => $request->narration,
            'user_id' => Auth::id(),
        ]);

        return $this->success($expense,'expense updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $expense)
    {
        //
        $expense = Expense::findOrFail($expense);
        $expense->delete();

    }
}
