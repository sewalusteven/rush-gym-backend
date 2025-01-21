<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
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
            //
            'amount' => 'required | numeric',
            'serviceId' => 'required |exists:services,id',
            'paymentMethodId' => 'required |exists:payment_methods,id',
            'memberId' => 'exists:members,id|nullable',
            'paymentDate' => 'required | date'
        ];
    }
}
