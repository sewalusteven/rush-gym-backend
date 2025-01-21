<?php

namespace App\Http\Resources;

use App\Http\Enums\TransactionCategory;
use App\Models\Expense;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $expenseDetails = null;
        $saleDetails = null;

        if($this['type'] == 'debit'){
            $expense = Expense::where('transaction_id', $this['id'])->first();
            $expenseDetails = [
                'id' => $expense['id'],
                'amount' => $expense['amount'],
                'narration' => $expense['narration'],
            ];
        }

        if($this['type'] == 'credit' && $this['category'] == TransactionCategory::SERVICE_INCOME){
            $sale = Sale::where('transaction_id', $this['id'])->first();
            $member = $sale['member'];
            if($member){
                $member = [
                    'id' => $sale['member']['id'],
                    'name' => $sale['member']['name'],
                    'phoneNumber' => $sale['member']['phone_number'],
                ];
            }

            $saleDetails = [
                'id' => $sale['id'],
                'amount' => $sale['amount'],
                'service' => [
                    'id' => $sale['service']['id'],
                    'name' => $sale['service']['service'],
                ],
                'member' => $member
            ];
        }

        return [
            "id" => $this['id'],
            "amount" => $this['amount'],
            "type" => $this['type'],
            "narration" => $this['narration'],
            "createdAt" => $this['created_at'],
            'category' => $this['category'],
            "transactionDate" => $this['transaction_date'],
            "paymentMethod" => [
                'id' => $this['paymentMethod']['id'],
                'method' => $this['paymentMethod']['method'],
            ],
            'expense' => $expenseDetails,
            'sale' => $saleDetails,
        ];
    }
}
