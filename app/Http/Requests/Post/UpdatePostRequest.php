<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MaxTotalMedia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\ValidationFailedException;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId=Auth::id();
        $postId=$this->route('post');
        return [
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'photos.*' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:2048',
                new MaxTotalMedia($userId, 3)
            ],
            'videos.*' => [
                'nullable',
                'mimes:mp4',
                'max:200000',
                new MaxTotalMedia($userId, 3)
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
