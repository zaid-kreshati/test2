<?php

namespace App\Traits;

use App\Exceptions\ModelNotFoundException;
use Illuminate\Database\Eloquent\Model;

trait ChecksModelExistence
{
    /**
     * Check if model exists by ID
     *
     * @param string $modelClass
     * @param int $id
     * @return Model
     * @throws ModelNotFoundException
     */
    protected function checkModelExists(string $modelClass, int $id): Model
    {
        $model = $modelClass::find($id);

        if (!$model) {
            $modelName = class_basename($modelClass);
            throw new ModelNotFoundException($modelName, $id);
        }

        return $model;
    }
}
