<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Models\User;

class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'description' => $this->faker->paragraph(),
            'status' => 'published', // default status
            'category_id' => Category::first()->id,
            'owner_id' => User::first()->id,
        ];
    }

    public function draft()
    {
        return $this->state(function (array $attributes) {
            return ['status' => 'draft'];
        });
    }

    public function archived()
    {
        return $this->state(function (array $attributes) {
            return ['status' => 'archived'];
        });
    }
}
