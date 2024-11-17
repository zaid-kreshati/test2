<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Traits\JsonResponseTrait; // Include the trait


class ValidationFailedException extends Exception
{
    use JsonResponseTrait; // Use the JsonResponseTrait

    protected $validator;

    public function __construct(Validator $validator)
    {
        parent::__construct('Validation failed');
        $this->validator = $validator;
    }

    public function render()
    {
        return $this->errorResponse('Validation failed');

    }
}
