<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ModelNotFoundException extends Exception
{
    protected $model;
    protected $id;

    public function __construct($model, $id)
    {
        $this->model = $model;
        $this->id = $id;
        parent::__construct("No {$model} found with ID {$id}");
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $this->getMessage(),
            'model' => $this->model,
            'id' => $this->id
        ], 404);
    }
}
