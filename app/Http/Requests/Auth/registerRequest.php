<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use App\Exceptions\ValidationFailedException;
use Illuminate\Contracts\Validation\Validator;

class registerRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255', // Ensure this is correct
            'password' => 'required|string|min:8|confirmed'
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationFailedException($validator);
    }

}
