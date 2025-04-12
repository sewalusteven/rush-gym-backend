<?php

namespace App\Http\Controllers;

use App\Http\Enums\TransactionCategory;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Http\Resources\ExpenseResource;
use App\Http\Traits\HttpResponses;
use App\Models\Expense;
use App\Models\PaymentMethod;
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
        $expenses = Expense::orderBy('created_at','desc')->paginate($request->input('perPage'));
        if($request->input('search')){
            $expenses = Expense::where('narration','like',"%".$request->input('search')."%")->orderBy('created_at','desc')->paginate($request->input('perPage'));
        }

        return ExpenseResource::collection($expenses);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function counts()
    {
        //
        $daily =  Expense::getDailyExpenses();
        $monthly = Expense::getMonthlyExpenses();
        return $this->success(['daily' => $daily, 'monthly' => $monthly]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpenseRequest $request)
    {
        //
        $request = $request->validated();
        $paymentMethod = PaymentMethod::find($request['paymentMethodId']);

        $transaction = Transaction::create([
            'amount' => $request['amount'],
            'narration' => $request['narration'],
            'type' => 'debit',
            'transaction_date' => date('Y-m-d', strtotime($request['paymentDate'])),
            'category' => TransactionCategory::EXPENSE,
            'payment_method_id' => $paymentMethod['id'],
        ]);

        $expense = Expense::create([
            'amount' => $request['amount'],
            'narration' => $request['narration'],
            'user_id' => Auth::id(),
            'transaction_id' => $transaction['id'],
            'expense_date' => date('Y-m-d', strtotime($request['paymentDate']))
        ]);

        return $this->success($expense,'expense added successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $expense)
    {
        //
        $expense = Expense::findOrFail($expense);

        $transaction = Transaction::query();
        $transaction->where('category', TransactionCategory::EXPENSE);
        $transaction->where('narration', $expense['narration']);
        $transaction->where('amount', $expense['amount']);
        $transaction->where('transaction_date', $expense['expense_date']);
        $transaction->delete();

        $expense->delete();
        return response(['message' => 'transaction deleted successfully']);

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


}
