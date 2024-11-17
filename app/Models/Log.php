<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'model',
        'model_id',
        'action',
        'old_model',
        'new_model',
        'deleted_model',
        'updatedTime',
        'deletedTime',
        'createdTime',
        'action_by',
    ];

    protected $casts = [
        'old_model' => 'array',
        'new_model' => 'array',
        'deleted_model' => 'array',

    ];




}
