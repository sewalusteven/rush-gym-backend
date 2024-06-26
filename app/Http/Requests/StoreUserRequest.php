<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class StoreUserRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //
            'name' => ['required','string'],
            'email' => ['required','string','max:255','unique:users','email:rfc,dns'],
            'password' => ['required','confirmed'],
            'image' => ['required','image','mimes:jpeg,png,jpg,gif','max:2048','dimensions:min_width=300,min_height=300'],
        ];
    }
}
