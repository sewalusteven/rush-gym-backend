<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivePlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'memberId' => 'required|exists:members,id',
            'planId' => 'required|exists:membership_plans,id',
            'paymentMethodId' => 'required|exists:payment_methods,id',
            'startDate' => 'required|date',
            'paymentDate' => 'required|date',
            'deposit' => 'required|numeric',
        ];
    }
}
