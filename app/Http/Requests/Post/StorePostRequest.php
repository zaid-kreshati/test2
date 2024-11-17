<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\ValidationFailedException;
use Illuminate\Contracts\Validation\Validator;
use App\Rules\MaxTotalMedia;
use Illuminate\Support\Facades\Auth;


class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId=Auth::id();
        return [
            'description' => 'required|string|max:255',
            //'category_id' => 'required|exists:categories,id',
            'photos.*' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:2048',
            ],
            'videos.*' => [
                'nullable',
                'mimes:mp4',
                'max:200000',
            ],

        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new ValidationFailedException($validator);
    }

    public function messages()
    {
        return [
            'photos.*.max' => 'Each photo must not exceed 2MB.',
            'videos.*.max' => 'Each video must not exceed 200MB.',
            'photos.*.mimes' => 'Photos must be in jpeg, png, jpg, or gif format.',
            'videos.*.mimes' => 'Videos must be in mp4 format.',
        ];
    }



}
